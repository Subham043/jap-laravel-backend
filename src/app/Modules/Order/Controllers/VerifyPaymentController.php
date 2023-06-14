<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Requests\VerifyPaymentRequest;
use App\Modules\Order\Resources\OrderCollection;
use App\Modules\Order\Services\OrderService;

class VerifyPaymentController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function post(VerifyPaymentRequest $request){

        try {
            //code...
            $order = $this->orderService->verify_payment($request->validated());

            return response()->json([
                'message' => "Payment done successfully.",
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
