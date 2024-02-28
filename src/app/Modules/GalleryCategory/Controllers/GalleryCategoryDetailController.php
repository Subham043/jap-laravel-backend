<?php

namespace App\Modules\GalleryCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GalleryCategory\Resources\GalleryCategoryCollection;
use App\Modules\GalleryCategory\Services\GalleryCategoryService;

class GalleryCategoryDetailController extends Controller
{
    private $categoryService;

    public function __construct(GalleryCategoryService $categoryService)
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
        $this->categoryService = $categoryService;
    }

    public function get($id){
        $category = $this->categoryService->getById($id);

        return response()->json([
            'category' => GalleryCategoryCollection::make($category),
        ], 200);
    }

}
