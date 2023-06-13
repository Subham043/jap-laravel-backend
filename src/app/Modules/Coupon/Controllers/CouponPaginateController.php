<?php

namespace App\Modules\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coupon\Resources\CouponCollection;
use App\Modules\Coupon\Services\CouponService;
use Illuminate\Http\Request;

class CouponPaginateController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
        $this->couponService = $couponService;
    }

    public function get(Request $request){
        $data = $this->couponService->paginate($request->total ?? 10);
        return CouponCollection::collection($data);
    }

}
