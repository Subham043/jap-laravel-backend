<?php

namespace App\Modules\ProductImage\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductImage\Requests\ProductImageCreateRequest;
use App\Modules\ProductImage\Resources\ProductImageCollection;
use App\Modules\ProductImage\Services\ProductImageService;

class ProductImageCreateController extends Controller
{
    private $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->middleware('permission:create products', ['only' => ['post']]);
        $this->productImageService = $productImageService;
    }

    public function post(ProductImageCreateRequest $request, $product_id){

        try {
            //code...
            $productImage = $this->productImageService->create(
                [
                    ...$request->safe()->except(['image']),
                    'product_id' => $product_id,
                    'user_id' => auth()->user()->id,
                ]
            );

            if($request->hasFile('image')){
                $this->productImageService->saveFile($productImage, 'image');
            }

            return response()->json([
                'message' => "Product Image created successfully.",
                'product_image' => ProductImageCollection::make($productImage),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
