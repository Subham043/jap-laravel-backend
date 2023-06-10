<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Requests\CategoryCreateRequest;
use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\Category\Services\CategoryService;

class CategoryCreateController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('permission:create categories', ['only' => ['get','post']]);
        $this->categoryService = $categoryService;
    }

    public function post(CategoryCreateRequest $request){

        try {
            //code...
            $category = $this->categoryService->create(
                $request->safe()->except(['banner_image', 'icon_image'])
            );

            if($request->hasFile('banner_image')){
                $this->categoryService->saveFile($category, 'banner_image');
            }

            if($request->hasFile('icon_image')){
                $this->categoryService->saveFile($category, 'icon_image');
            }

            return response()->json([
                'message' => "Category created successfully.",
                'category' => CategoryCollection::make($category),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
