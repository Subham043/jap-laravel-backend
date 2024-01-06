<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Resources\OrderLatestBillingInfoCollection;
use App\Modules\Order\Services\OrderService;

class OrderLatestBillingInfoController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function get(){
        $order = $this->orderService->getLatest();

        return response()->json([
            'order' => $order ? OrderLatestBillingInfoCollection::make($order) : null,
        ], 200);
    }

}
