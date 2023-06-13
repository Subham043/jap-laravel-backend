<?php

namespace App\Modules\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupon\Requests\CouponCreateRequest;
use App\Modules\Coupon\Resources\CouponCollection;
use App\Modules\Coupon\Services\CouponService;

class CouponCreateController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware('permission:create coupons', ['only' => ['post']]);
        $this->couponService = $couponService;
    }

    public function post(CouponCreateRequest $request){

        try {
            //code...
            $coupon = $this->couponService->create(
                [
                    ...$request->validated(),
                    'user_id' => auth()->user()->id
                ]
            );

            return response()->json([
                'message' => "Coupon created successfully.",
                'coupon' => CouponCollection::make($coupon),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
