<?php

namespace App\Modules\Pincode\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Pincode\Requests\PincodeRequest;
use App\Modules\Pincode\Resources\PincodeCollection;
use App\Modules\Pincode\Services\PincodeService;

class PincodeUpdateController extends Controller
{
    private $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->middleware('permission:edit pincodes', ['only' => ['post']]);
        $this->pincodeService = $pincodeService;
    }

    public function post(PincodeRequest $request, $id){
        $pincode = $this->pincodeService->getById($id);
        try {
            //code...
            $this->pincodeService->update(
                [...$request->validated()],
                $pincode
            );

            return response()->json([
                'message' => "Pincode updated successfully.",
                'pincode' => PincodeCollection::make($pincode),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
