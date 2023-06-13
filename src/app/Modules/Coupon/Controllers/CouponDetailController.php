<?php

namespace App\Modules\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupon\Resources\CouponCollection;
use App\Modules\Coupon\Services\CouponService;

class CouponDetailController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
        $this->couponService = $couponService;
    }

    public function get($id){
        $coupon = $this->couponService->getById($id);

        return response()->json([
            'coupon' => CouponCollection::make($coupon),
        ], 200);
    }

}
