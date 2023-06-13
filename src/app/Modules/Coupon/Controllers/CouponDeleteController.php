<?php

namespace App\Modules\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupon\Services\CouponService;

class CouponDeleteController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware('permission:delete coupons', ['only' => ['delete']]);
        $this->couponService = $couponService;
    }

    public function delete($id){
        $coupon = $this->couponService->getById($id);

        try {
            //code...
            $this->couponService->delete(
                $coupon
            );
            return response()->json([
                'message' => "Coupon deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
