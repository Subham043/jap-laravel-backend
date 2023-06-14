<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Resources\OrderCollection;
use App\Modules\Order\Services\OrderService;

class OrderPlacedCancelController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function get($receipt){
        $order = $this->orderService->cancel($receipt);

        return response()->json([
            'message' => "Order cancelled successfully.",
            'order' => OrderCollection::make($order),
        ], 200);
    }

}
