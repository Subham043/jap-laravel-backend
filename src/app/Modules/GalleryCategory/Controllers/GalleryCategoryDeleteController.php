<?php

namespace App\Modules\GalleryCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GalleryCategory\Services\GalleryCategoryService;

class GalleryCategoryDeleteController extends Controller
{
    private $categoryService;

    public function __construct(GalleryCategoryService $categoryService)
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
                'message' => "GalleryCategory deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
