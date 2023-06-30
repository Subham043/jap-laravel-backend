<?php

namespace App\Modules\Tax\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tax\Resources\TaxCollection;
use App\Modules\Tax\Services\TaxService;

class TaxDetailController extends Controller
{
    private $taxService;

    public function __construct(TaxService $taxService)
    {
        $this->middleware('permission:list taxes', ['only' => ['get']]);
        $this->taxService = $taxService;
    }

    public function get(){
        $tax = $this->taxService->get();

        if($tax){
            return response()->json([
                'tax' => TaxCollection::make($tax),
            ], 200);
        }

        return response()->json([
            'tax' => 0,
        ], 200);
    }

}
