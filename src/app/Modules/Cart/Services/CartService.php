<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Models\Cart;

class CartService
{

    public function get(): Cart|null
    {
        $data = Cart::with(['products'])->where('user_id', auth()->user()->id)->first();
        if(!$data){
            $data = $data = $this->save(['user_id'=>auth()->user()->id]);
        }
        return $data;
    }

    public function save(array $data): Cart
    {
        $cart = Cart::updateOrCreate(['user_id'=>auth()->user()->id],$data);
        return $cart;
    }

}
