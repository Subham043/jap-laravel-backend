<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\Category\Services\CategoryService;

class CategoryDetailController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
        $this->categoryService = $categoryService;
    }

    public function get($id){
        $category = $this->categoryService->getById($id);

        return response()->json([
            'category' => CategoryCollection::make($category),
        ], 200);
    }

}
