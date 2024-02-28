<?php

namespace App\Modules\Gallery\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Requests\GalleryUpdateRequest;
use App\Modules\Gallery\Resources\GalleryCollection;
use App\Modules\Gallery\Services\GalleryService;

class GalleryUpdateController extends Controller
{
    private $productService;

    public function __construct(GalleryService $productService)
    {
        $this->middleware('permission:edit products', ['only' => ['post']]);
        $this->productService = $productService;
    }

    public function post(GalleryUpdateRequest $request, $id){
        $product = $this->productService->getById($id);
        try {
            //code...
            $product = $this->productService->update(
                [
                    ...$request->safe()->except(['image', 'category']),
                    'gallery_categories_id' => $request->category
                ],
                $product
            );

            if($request->hasFile('image')){
                $this->productService->saveFile($product, 'image');
            }

            return response()->json([
                'message' => "Gallery updated successfully.",
                'product' => GalleryCollection::make($product),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
