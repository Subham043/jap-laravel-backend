<?php

namespace App\Modules\Order\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMode;
use App\Enums\PaymentStatus;
use App\Http\Services\RazorpayService;
use App\Modules\Cart\Services\CartService;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Webpatser\Uuid\Uuid;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OrderService
{
    public function placed_paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])
        ->withCount(['products'])
        ->where('user_id', auth()->user()->id)
        ->where(function($q){
            $q->where('mode_of_payment', PaymentMode::COD->value)
            ->orWhere(function($query){
                $query->where('mode_of_payment', PaymentMode::ONLINE->value)
                ->where(function($q){
                    $q->where('payment_status', PaymentStatus::PAID->value)->orWhere('payment_status', PaymentStatus::REFUND->value);
                });
            });
        })->latest();
        return QueryBuilder::for($query)
                ->paginate($total)
                ->appends(request()->query());
    }

    public function paginate(Int $total = 10): LengthAwarePaginator
    {
        $query = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])
        ->withCount(['products'])
        ->where(function($q){
            $q->where('mode_of_payment', PaymentMode::COD->value)
            ->orWhere(function($query){
                $query->where('mode_of_payment', PaymentMode::ONLINE->value)
                ->where(function($q){
                    $q->where('payment_status', PaymentStatus::PAID->value)->orWhere('payment_status', PaymentStatus::REFUND->value);
                });
            });
        })->latest();
        return QueryBuilder::for($query)
                ->defaultSort('id')
                ->allowedSorts('id', 'mode_of_payment', 'order_status', 'payment_status')
                ->allowedFilters([
                    'mode_of_payment',
                    'order_status',
                    'payment_status',
                    AllowedFilter::custom('search', new CommonFilter),
                ])
                ->paginate($total)
                ->appends(request()->query());
    }

    public function getByReceipt(string $receipt): Order|null
    {
        $order = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])
        ->withCount(['products'])
        ->where('receipt', $receipt)
        ->where('user_id', auth()->user()->id)
        ->where(function($q){
            $q->where('mode_of_payment', PaymentMode::COD->value)
            ->orWhere(function($query){
                $query->where('mode_of_payment', PaymentMode::ONLINE->value)
                ->where(function($q){
                    $q->where('payment_status', PaymentStatus::PAID->value)->orWhere('payment_status', PaymentStatus::REFUND->value);
                });
            });
        })
        ->firstOrFail();
        return $order;
    }

    public function getById(string $id): Order|null
    {
        $order = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])
        ->withCount(['products'])
        ->where('id', $id)
        ->firstOrFail();
        return $order;
    }

    public function update_status(array $data, Order $order): Order
    {
        $order->update($data);
        return $order;
    }

    public function cancel(string $receipt): Order|null
    {
        $order = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])
        ->withCount(['products'])
        ->where('receipt', $receipt)
        ->where('user_id', auth()->user()->id)
        ->where(function($q){
            $q->where('mode_of_payment', PaymentMode::COD->value)
            ->orWhere(function($query){
                $query->where('mode_of_payment', PaymentMode::ONLINE->value)
                ->where(function($q){
                    $q->where('payment_status', PaymentStatus::PAID->value)->orWhere('payment_status', PaymentStatus::REFUND->value);
                });
            });
        })
        ->firstOrFail();
        $order->order_status = OrderStatus::CANCELLED->value;
        $order->payment_status = PaymentStatus::REFUND->value;
        if($order->mode_of_payment==PaymentMode::ONLINE && !empty($order->razorpay_payment_id)){
            $price = $order->coupon_discount > 0 ? $order->total_price - $order->coupon_discount : $order->total_price;
            (new RazorpayService)->refund($price, $order->razorpay_payment_id);
        }
        $order->save();
        return $order;
    }

    public function verify_payment(array $data): Order
    {
        $order = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])->withCount(['products'])->where('razorpay_order_id', $data['razorpay_order_id'])->firstOrFail();
        $order->razorpay_payment_id = $data['razorpay_payment_id'];
        $order->razorpay_signature = $data['razorpay_signature'];
        $order->payment_status = PaymentStatus::PAID;
        $order->save();
        foreach ($order->products as $value) {
            if($order->mode_of_payment == PaymentMode::ONLINE){
                $this->update_product_inventory($value, $value->pivot->quantity);
            }
        }
        return $order;
    }

    public function place_order(array $data): Order
    {
        $order = Order::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])->withCount(['products'])->create(
            [...$data, 'user_id'=>auth()->user()->id, 'coupon_id' => null, 'coupon_discount' => 0]);
        return $this->save_products_coupons($order);
    }

    public function save_products_coupons(Order $order): Order
    {
        $cart = (new CartService)->get();
        $order->sub_total = $cart->sub_total;
        $order->total_discount = $cart->total_discount;
        $order->coupon_discount = $cart->coupon_discount;
        $order->gst_charge = $cart->gst_charge;
        $order->delivery_charge = $cart->delivery_charge;
        $order->coupon_id = $cart->coupon_id;
        $order->total_price = $cart->total_price;
        $order->receipt = Uuid::generate(4)->string;
        if($order->mode_of_payment == PaymentMode::ONLINE){
            $price = $cart->coupon_discount > 0 ? $cart->total_price - $cart->coupon_discount : $cart->total_price;
            $order->razorpay_order_id = (new RazorpayService)->create_order_id($price, $order->receipt);
        }else{
            $order->razorpay_order_id = null;
        }
        if($cart->products->count() > 0){
            $product_array = array();
            $products_array = array();
            foreach ($cart->products as $value) {
                # code...
                $product_array['quantity'] = $value->pivot->quantity;
                $product_array['product_id'] = $value->id;
                $product_array['product_name'] = $value->name;
                $product_array['product_slug'] = $value->slug;
                $product_array['product_description'] = $value->description;
                $product_array['product_price'] = $value->price;
                $product_array['product_dicount'] = $value->discount;
                array_push($products_array,$product_array);
                if($order->mode_of_payment == PaymentMode::COD){
                    $this->update_product_inventory($value, $value->pivot->quantity);
                }
            }
            $order->products()->sync($products_array);
        }
        if($cart->coupon_id){
            $order->coupon_name = $cart->coupon->name;
            $order->coupon_code = $cart->coupon->code;
            $order->coupon_discount_percentage = $cart->coupon->discount;
            $order->coupon_maximum_discount = $cart->coupon->maximum_dicount_in_price;
            $order->coupon_maximum_use = $cart->coupon->maximum_number_of_use;
        }
        $order->save();
        return $order;
    }

    protected function update_product_inventory(Product $product, int $quantity): void
    {
        $product->inventory = $product->inventory - $quantity;
        $product->save();
    }

}

class CommonFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('sub_total', 'LIKE', '%' . $value . '%')
        ->orWhere('total_price', 'LIKE', '%' . $value . '%')
        ->orWhere('total_discount', 'LIKE', '%' . $value . '%')
        ->orWhere('coupon_discount', 'LIKE', '%' . $value . '%')
        ->orWhere('coupon_name', 'LIKE', '%' . $value . '%')
        ->orWhere('coupon_code', 'LIKE', '%' . $value . '%')
        ->orWhere('coupon_discount_percentage', 'LIKE', '%' . $value . '%')
        ->orWhere('coupon_maximum_discount', 'LIKE', '%' . $value . '%')
        ->orWhere('coupon_maximum_use', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_first_name', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_last_name', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_email', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_phone', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_country', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_state', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_city', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_pin', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_address_1', 'LIKE', '%' . $value . '%')
        ->orWhere('billing_address_2', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_first_name', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_last_name', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_email', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_phone', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_country', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_state', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_city', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_pin', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_address_1', 'LIKE', '%' . $value . '%')
        ->orWhere('shipping_address_2', 'LIKE', '%' . $value . '%')
        ->orWhere('order_notes', 'LIKE', '%' . $value . '%')
        ->orWhere('mode_of_payment', 'LIKE', '%' . $value . '%')
        ->orWhere('order_status', 'LIKE', '%' . $value . '%')
        ->orWhere('payment_status', 'LIKE', '%' . $value . '%')
        ->orWhere('razorpay_order_id', 'LIKE', '%' . $value . '%')
        ->orWhere('receipt', 'LIKE', '%' . $value . '%');
    }
}
