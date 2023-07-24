<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\ProductCreateRequest;
use App\Modules\Product\Resources\ProductCollection;
use App\Modules\Product\Services\ProductService;

class ProductCreateController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('permission:create products', ['only' => ['post']]);
        $this->productService = $productService;
    }

    public function post(ProductCreateRequest $request){

        try {
            //code...
            $product = $this->productService->create(
                [
                    ...$request->safe()->except(['featured_image', 'category', 'pincode']),
                    'user_id' => auth()->user()->id
                ]
            );

            if($request->category && count($request->category)>0){
                $product->categories()->sync($request->category);
            }

            // if($request->pincode && count($request->pincode)>0){
            //     $product->pincodes()->sync($request->pincode);
            // }

            if($request->hasFile('featured_image')){
                $this->productService->saveFile($product, 'featured_image');
            }

            return response()->json([
                'message' => "Product created successfully.",
                'product' => ProductCollection::make($product),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
