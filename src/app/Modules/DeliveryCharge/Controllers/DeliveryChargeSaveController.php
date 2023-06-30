<?php

namespace App\Modules\DeliveryCharge\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DeliveryCharge\Requests\DeliveryChargeRequest;
use App\Modules\DeliveryCharge\Resources\DeliveryChargeCollection;
use App\Modules\DeliveryCharge\Services\DeliveryChargeService;

class DeliveryChargeSaveController extends Controller
{
    private $deliveryChargeService;

    public function __construct(DeliveryChargeService $deliveryChargeService)
    {
        $this->middleware('permission:save delivery charges', ['only' => ['post']]);
        $this->deliveryChargeService = $deliveryChargeService;
    }

    public function post(DeliveryChargeRequest $request){

        try {
            //code...
            $deliveryCharge = $this->deliveryChargeService->save(
                [
                    ...$request->validated(),
                ]
            );

            return response()->json([
                'message' => "DeliveryCharge saved successfully.",
                'deliveryCharge' => DeliveryChargeCollection::make($deliveryCharge),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
