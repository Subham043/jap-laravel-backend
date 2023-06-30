<?php

namespace App\Modules\DeliveryCharge\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DeliveryCharge\Resources\DeliveryChargeCollection;
use App\Modules\DeliveryCharge\Services\DeliveryChargeService;

class DeliveryChargeDetailController extends Controller
{
    private $deliveryChargeService;

    public function __construct(DeliveryChargeService $deliveryChargeService)
    {
        $this->middleware('permission:list delivery charges', ['only' => ['get']]);
        $this->deliveryChargeService = $deliveryChargeService;
    }

    public function get(){
        $deliveryCharge = $this->deliveryChargeService->get();

        if($deliveryCharge){
            return response()->json([
                'deliveryCharge' => DeliveryChargeCollection::make($deliveryCharge),
            ], 200);
        }

        return response()->json([
            'deliveryCharge' => 0,
        ], 200);

    }

}
