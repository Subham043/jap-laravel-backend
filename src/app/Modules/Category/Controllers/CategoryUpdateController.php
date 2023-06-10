<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Requests\CategoryUpdateRequest;
use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\Category\Services\CategoryService;

class CategoryUpdateController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('permission:edit categories', ['only' => ['get', 'post']]);
        $this->categoryService = $categoryService;
    }

    public function post(CategoryUpdateRequest $request, $id){
        $category = $this->categoryService->getById($id);
        try {
            //code...
            $this->categoryService->update(
                [...$request->safe()->except(['banner_image', 'icon_image'])],
                $category
            );

            if($request->hasFile('banner_image')){
                $this->categoryService->saveFile($category, 'banner_image');
            }

            if($request->hasFile('icon_image')){
                $this->categoryService->saveFile($category, 'icon_image');
            }

            return response()->json([
                'message' => "Category updated successfully.",
                'category' => CategoryCollection::make($category),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
