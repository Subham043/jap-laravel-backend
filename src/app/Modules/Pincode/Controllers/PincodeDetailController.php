<?php

namespace App\Modules\Pincode\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Pincode\Resources\PincodeCollection;
use App\Modules\Pincode\Services\PincodeService;

class PincodeDetailController extends Controller
{
    private $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->middleware('permission:list pincodes', ['only' => ['get']]);
        $this->pincodeService = $pincodeService;
    }

    public function get($id){
        $pincode = $this->pincodeService->getById($id);

        return response()->json([
            'pincode' => PincodeCollection::make($pincode),
        ], 200);
    }

}
