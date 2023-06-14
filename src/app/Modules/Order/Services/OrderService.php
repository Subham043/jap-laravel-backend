<?php

namespace App\Modules\Order\Services;

use App\Enums\PaymentMode;
use App\Enums\PaymentStatus;
use App\Http\Services\RazorpayService;
use App\Modules\Cart\Services\CartService;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Webpatser\Uuid\Uuid;

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
                $query->where('mode_of_payment', PaymentMode::ONLINE->value)->where('payment_status', PaymentStatus::PAID->value);
            });
        })->latest();
        return QueryBuilder::for($query)
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
                $query->where('mode_of_payment', PaymentMode::ONLINE->value)->where('payment_status', PaymentStatus::PAID->value);
            });
        })
        ->firstOrFail();
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
