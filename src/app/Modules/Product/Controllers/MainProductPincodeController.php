<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\ProductPincodeRequest;
use App\Modules\Product\Services\ProductService;

class MainProductPincodeController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function post(ProductPincodeRequest $request, $slug){
        $product = $this->productService->getBySlug($slug);
        $pincodes = $product->pincodes;
        $availability = false;
        if($pincodes->count()==0){
            $availability = true;
            return response()->json([
                'message' => "Product available for the given pincode.",
                'availability' => $availability,
            ], 200);
        }

        foreach ($pincodes as $key => $value) {
            # code...
            // if($request->pincode >= $value->min_pincode && $request->pincode <= $value->max_pincode){
            //     $availability = true;
            //     return response()->json([
            //         'message' => "Product available for the given pincode.",
            //         'availability' => $availability,
            //     ], 200);
            // }
            if($request->pincode == $value->pincode){
                $availability = true;
                return response()->json([
                    'message' => "Product available for the given pincode.",
                    'availability' => $availability,
                ], 200);
            }
        }
        return response()->json([
            'message' => "Product not available for the given pincode.",
            'availability' => $availability,
        ], 400);

    }
}
