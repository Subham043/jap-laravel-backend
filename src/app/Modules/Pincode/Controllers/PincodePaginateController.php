<?php

namespace App\Modules\Pincode\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Pincode\Resources\PincodeCollection;
use App\Modules\Pincode\Services\PincodeService;
use Illuminate\Http\Request;

class PincodePaginateController extends Controller
{
    private $pincodeService;

    public function __construct(PincodeService $pincodeService)
    {
        $this->middleware('permission:list pincodes', ['only' => ['get']]);
        $this->pincodeService = $pincodeService;
    }

    public function get(Request $request){
        $data = $this->pincodeService->paginate($request->total ?? 10);
        return PincodeCollection::collection($data);
    }

}
