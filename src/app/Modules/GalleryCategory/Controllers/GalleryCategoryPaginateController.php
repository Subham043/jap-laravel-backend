<?php

namespace App\Modules\GalleryCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GalleryCategory\Resources\GalleryCategoryCollection;
use App\Modules\GalleryCategory\Services\GalleryCategoryService;
use Illuminate\Http\Request;

class GalleryCategoryPaginateController extends Controller
{
    private $categoryService;

    public function __construct(GalleryCategoryService $categoryService)
    {
        $this->middleware('permission:list categories', ['only' => ['get']]);
        $this->categoryService = $categoryService;
    }

    public function get(Request $request){
        $data = $this->categoryService->paginate($request->total ?? 10);
        return GalleryCategoryCollection::collection($data);
    }

}
