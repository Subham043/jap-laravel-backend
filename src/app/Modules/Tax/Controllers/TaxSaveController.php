<?php

namespace App\Modules\Tax\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tax\Requests\TaxRequest;
use App\Modules\Tax\Resources\TaxCollection;
use App\Modules\Tax\Services\TaxService;

class TaxSaveController extends Controller
{
    private $taxService;

    public function __construct(TaxService $taxService)
    {
        $this->middleware('permission:save taxes', ['only' => ['post']]);
        $this->taxService = $taxService;
    }

    public function post(TaxRequest $request){

        try {
            //code...
            $tax = $this->taxService->save(
                [
                    ...$request->validated(),
                ]
            );

            return response()->json([
                'message' => "Tax saved successfully.",
                'tax' => TaxCollection::make($tax),
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
