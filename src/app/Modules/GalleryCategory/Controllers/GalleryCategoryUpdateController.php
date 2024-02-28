<?php

namespace App\Modules\GalleryCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GalleryCategory\Requests\GalleryCategoryRequest;
use App\Modules\GalleryCategory\Resources\GalleryCategoryCollection;
use App\Modules\GalleryCategory\Services\GalleryCategoryService;

class GalleryCategoryUpdateController extends Controller
{
    private $categoryService;

    public function __construct(GalleryCategoryService $categoryService)
    {
        $this->middleware('permission:edit categories', ['only' => ['post']]);
        $this->categoryService = $categoryService;
    }

    public function post(GalleryCategoryRequest $request, $id){
        $category = $this->categoryService->getById($id);
        try {
            //code...
            $this->categoryService->update(
                [...$request->safe()->except(['banner_image', 'icon_image'])],
                $category
            );

            return response()->json([
                'message' => "GalleryCategory updated successfully.",
                'category' => GalleryCategoryCollection::make($category),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
