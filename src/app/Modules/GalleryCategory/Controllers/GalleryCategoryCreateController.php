<?php

namespace App\Modules\GalleryCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GalleryCategory\Requests\GalleryCategoryRequest;
use App\Modules\GalleryCategory\Resources\GalleryCategoryCollection;
use App\Modules\GalleryCategory\Services\GalleryCategoryService;

class GalleryCategoryCreateController extends Controller
{
    private $categoryService;

    public function __construct(GalleryCategoryService $categoryService)
    {
        $this->middleware('permission:create categories', ['only' => ['post']]);
        $this->categoryService = $categoryService;
    }

    public function post(GalleryCategoryRequest $request){

        try {
            //code...
            $category = $this->categoryService->create(
                [
                    ...$request->safe()->except(['banner_image', 'icon_image']),
                    'user_id' => auth()->user()->id
                ]
            );

            return response()->json([
                'message' => "GalleryCategory created successfully.",
                'category' => GalleryCategoryCollection::make($category),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
