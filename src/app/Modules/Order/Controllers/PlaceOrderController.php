<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Requests\OrderRequest;
use App\Modules\Order\Resources\OrderCollection;
use App\Modules\Order\Services\OrderService;

class PlaceOrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function post(OrderRequest $request){

        try {
            //code...
            $order = $this->orderService->place_order($request->safe()->except(['order', 'coupon_code']));

            return response()->json([
                'message' => "Order Placed successfully.",
                'order' => OrderCollection::make($order),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
