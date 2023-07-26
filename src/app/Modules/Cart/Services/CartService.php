<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Models\Cart;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\DeliveryCharge\Services\DeliveryChargeService;
use App\Modules\Tax\Services\TaxService;
use Illuminate\Support\Facades\DB;

class CartService
{

    public function get(): Cart|null
    {
        Cart::whereNull('user_id')->delete();
        $data = Cart::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])->withCount(['products'])->where('user_id', auth()->user()->id)->first();
        if(!$data){
            $data = $data = $this->save(['user_id'=>auth()->user()->id]);
        }
        return $data;
    }

    public function save(array $data): Cart
    {
        $cart = Cart::with([
            'products' => function($q) {
                $q->with(['categories']);
            },
            'coupon'
        ])->withCount(['products'])->updateOrCreate(
            ['user_id'=>auth()->user()->id],
            [...$data, 'coupon_id' => null, 'coupon_discount' => 0]);
        return $cart;
    }

    public function apply_coupon(string $coupon): Cart
    {
        $coupon = Coupon::where('code', $coupon)->firstOrFail();
        $cart = $this->get();
        $cart->coupon_id = $coupon->id;
        $cart->save();
        $this->calculate_cart_price();
        return $cart;
    }

    public function save_products(Cart $cart, array $data): Cart
    {
        DB::table('cart_products')->where('cart_id', $cart->id)->whereNull('product_id')->delete();
        $cart->products()->sync($data);
        $this->calculate_cart_price();
        return $cart;
    }

    protected function calculate_cart_price(): void
    {
        $cart = $this->get();
        $gst = (new TaxService)->get();
        $gst_charge = 0;
        $delivery_charge = (new DeliveryChargeService)->get();
        $delivery_charge_amt = 0;
        $sub_total = 0;
        $total_discount = 0;
        $coupon_discount = 0;
        $total_price = 0;
        if($cart->products->count() > 0){
            foreach ($cart->products as $value) {
                # code...
                $total_qt_price = $value->price * $value->pivot->quantity;
                $sub_total = $sub_total + $total_qt_price;
                $discount = $total_qt_price * ($value->discount/100);
                $total_discount = $total_discount + $discount;
                $total_price = $total_price + ($total_qt_price - $discount);
            }
        }
        if($gst){
            $gst_charge = $total_price * ($gst->tax_in_percentage/100);
        }
        if($delivery_charge && $total_price < $delivery_charge->no_delivery_charges_for_cart_total_price_above){
            $delivery_charge_amt = $delivery_charge->delivery_charges;
        }
        if($cart->coupon_id){
            $coupon_discount = ($total_price + $gst_charge + $delivery_charge_amt) * ($cart->coupon->discount/100);
            if($cart->coupon->maximum_dicount_in_price > $coupon_discount){
               $coupon_discount = $cart->coupon->maximum_dicount_in_price;
            }
        }

        $cart->sub_total = round($sub_total, 2);
        $cart->gst_charge = round($gst_charge, 2);
        $cart->delivery_charge = round($delivery_charge_amt, 2);
        $cart->total_discount = round($total_discount, 2);
        $cart->coupon_discount = round($coupon_discount, 2);
        $cart->total_price = round($total_price, 2);
        $cart->save();
    }

}
