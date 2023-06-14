<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Resources\OrderCollection;
use App\Modules\Order\Services\OrderService;
use Illuminate\Http\Request;

class OrderPaginateController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('permission:list orders', ['only' => ['get']]);
        $this->orderService = $orderService;
    }

    public function get(Request $request){
        $order = $this->orderService->paginate($request->total ?? 10);
        return OrderCollection::collection($order);
    }

}
