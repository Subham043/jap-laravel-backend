<?php

namespace App\Modules\Pincode\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Pincode\Services\PincodeService;

class PincodeDeleteController extends Controller
{
    private $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->middleware('permission:delete pincodes', ['only' => ['delete']]);
        $this->pincodeService = $pincodeService;
    }

    public function delete($id){
        $pincode = $this->pincodeService->getById($id);

        try {
            //code...
            $this->pincodeService->delete(
                $pincode
            );
            return response()->json([
                'message' => "Pincode deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
