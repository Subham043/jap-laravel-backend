<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Resources\CartCollection;
use App\Modules\Cart\Services\CartService;

class CartDetailController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function get(){
        $cart = $this->cartService->get();

        return response()->json([
            'cart' => CartCollection::make($cart),
        ], 200);
    }

}
