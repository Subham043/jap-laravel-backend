<?php

namespace App\Modules\ProductImage\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductImage\Requests\ProductImageUpdateRequest;
use App\Modules\ProductImage\Resources\ProductImageCollection;
use App\Modules\ProductImage\Services\ProductImageService;

class ProductImageUpdateController extends Controller
{
    private $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->middleware('permission:edit products', ['only' => ['post']]);
        $this->productImageService = $productImageService;
    }

    public function post(ProductImageUpdateRequest $request, $product_id, $id){
        $productImage = $this->productImageService->getById($product_id, $id);
        try {
            //code...
            $productImage = $this->productImageService->update(
                [...$request->safe()->except(['image'])],
                $productImage
            );

            if($request->hasFile('image')){
                $this->productImageService->saveFile($productImage, 'image');
            }

            return response()->json([
                'message' => "Product Image updated successfully.",
                'product' => ProductImageCollection::make($productImage),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
