<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Requests\OrderStatusRequest;
use App\Modules\Order\Resources\OrderCollection;
use App\Modules\Order\Services\OrderService;

class OrderStatusController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('permission:edit orders', ['only' => ['post']]);
        $this->orderService = $orderService;
    }

    public function post(OrderStatusRequest $request, $id){
        $order = $this->orderService->getById($id);

        try {
            //code...
            $order = $this->orderService->update_status(
                [...$request->validated()],
                $order
            );

            return response()->json([
                'message' => "Status updated successfully.",
                'order' => OrderCollection::make($order),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
