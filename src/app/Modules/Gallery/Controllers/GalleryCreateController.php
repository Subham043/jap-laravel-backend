<?php

namespace App\Modules\Gallery\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Requests\GalleryCreateRequest;
use App\Modules\Gallery\Resources\GalleryCollection;
use App\Modules\Gallery\Services\GalleryService;

class GalleryCreateController extends Controller
{
    private $productService;

    public function __construct(GalleryService $productService)
    {
        $this->middleware('permission:create products', ['only' => ['post']]);
        $this->productService = $productService;
    }

    public function post(GalleryCreateRequest $request){

        try {
            //code...
            $product = $this->productService->create(
                [
                    ...$request->safe()->except(['image', 'category']),
                    'user_id' => auth()->user()->id,
                    'gallery_categories_id' => $request->category
                ]
            );

            if($request->hasFile('image')){
                $this->productService->saveFile($product, 'image');
            }

            return response()->json([
                'message' => "Gallery created successfully.",
                'product' => GalleryCollection::make($product),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
