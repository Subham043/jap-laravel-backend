<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Models\Cart;

class CartService
{

    public function get(): Cart|null
    {
        $data = Cart::with([
            'products' => function($q) {
                $q->with(['categories']);
            }
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
            }
        ])->withCount(['products'])->updateOrCreate(['user_id'=>auth()->user()->id],$data);
        return $cart;
    }

    public function save_products(Cart $cart, array $data): Cart
    {
        $cart->products()->sync($data);
        $this->calculate_cart_price();
        return $cart;
    }

    protected function calculate_cart_price(): void
    {
        $cart = $this->get();
        $sub_total = 0;
        $total_discount = 0;
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
        $cart->sub_total = $sub_total;
        $cart->total_discount = $total_discount;
        $cart->total_price = $total_price;
        $cart->save();
    }

}
