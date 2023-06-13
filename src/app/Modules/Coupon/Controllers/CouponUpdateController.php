<?php

namespace App\Modules\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupon\Requests\CouponUpdateRequest;
use App\Modules\Coupon\Resources\CouponCollection;
use App\Modules\Coupon\Services\CouponService;

class CouponUpdateController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware('permission:edit categories', ['only' => ['post']]);
        $this->couponService = $couponService;
    }

    public function post(CouponUpdateRequest $request, $id){
        $coupon = $this->couponService->getById($id);
        try {
            //code...
            $this->couponService->update(
                [...$request->validated()],
                $coupon
            );

            return response()->json([
                'message' => "Coupon updated successfully.",
                'coupon' => CouponCollection::make($coupon),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
