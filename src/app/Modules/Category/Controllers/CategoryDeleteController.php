<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Services\CategoryService;

class CategoryDeleteController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('permission:delete categories', ['only' => ['delete']]);
        $this->categoryService = $categoryService;
    }

    public function delete($id){
        $category = $this->categoryService->getById($id);

        try {
            //code...
            $this->categoryService->delete(
                $category
            );
            return response()->json([
                'message' => "Category deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
