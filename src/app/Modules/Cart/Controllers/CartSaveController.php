<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Requests\CartRequest;
use App\Modules\Cart\Resources\CartCollection;
use App\Modules\Cart\Services\CartService;

class CartSaveController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function post(CartRequest $request){

        try {
            //code...
            $cart = $this->cartService->save(
                [
                    ...$request->safe()->except(['data']),
                ]
            );

            if($request->data && count($request->data)>0){
                $this->cartService->save_products($cart, $request->data);
            }else{
                $this->cartService->save_products($cart, []);
            }

            return response()->json([
                'message' => "Cart created successfully.",
                'cart' => CartCollection::make($this->cartService->get()),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
