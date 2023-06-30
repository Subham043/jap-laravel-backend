<?php

namespace App\Modules\Pincode\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Pincode\Requests\PincodeRequest;
use App\Modules\Pincode\Resources\PincodeCollection;
use App\Modules\Pincode\Services\PincodeService;

class PincodeCreateController extends Controller
{
    private $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->middleware('permission:create pincodes', ['only' => ['post']]);
        $this->pincodeService = $pincodeService;
    }

    public function post(PincodeRequest $request){

        try {
            //code...
            $pincode = $this->pincodeService->create(
                [
                    ...$request->validated(),
                    'user_id' => auth()->user()->id
                ]
            );

            return response()->json([
                'message' => "Pincode created successfully.",
                'pincode' => PincodeCollection::make($pincode),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
