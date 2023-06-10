<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Resources\CategoryCollection;
use App\Modules\Category\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryPaginateController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
        $this->categoryService = $categoryService;
    }

    public function get(Request $request){
        $data = $this->categoryService->paginate($request->total ?? 10);
        return CategoryCollection::collection($data);
    }

}
