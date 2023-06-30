<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\Category\Services\CategoryService;

class MainCategoryDetailController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function get($slug){
        $category = $this->categoryService->getBySlug($slug);

        return response()->json([
            'category' => CategoryCollection::make($category),
        ], 200);
    }

}
