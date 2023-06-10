<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\ProductUpdateRequest;
use App\Modules\Product\Resources\ProductCollection;
use App\Modules\Product\Services\ProductService;

class ProductUpdateController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('permission:edit products', ['only' => ['post']]);
        $this->productService = $productService;
    }

    public function post(ProductUpdateRequest $request, $id){
        $product = $this->productService->getById($id);
        try {
            //code...
            $product = $this->productService->update(
                [...$request->safe()->except(['featured_image', 'category'])],
                $product
            );

            if($request->category && count($request->category)>0){
                $product->categories()->sync($request->category);
            }

            if($request->hasFile('featured_image')){
                $this->productService->saveFile($product, 'featured_image');
            }

            return response()->json([
                'message' => "Product updated successfully.",
                'product' => ProductCollection::make($product),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
