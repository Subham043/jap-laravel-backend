<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Requests\CouponRequest;
use App\Modules\Cart\Resources\CartCollection;
use App\Modules\Cart\Services\CartService;

class ApplyCouponController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function post(CouponRequest $request){

        try {
            //code...
            $this->cartService->apply_coupon($request->coupon_code);

            return response()->json([
                'message' => "Coupon Applied successfully.",
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
