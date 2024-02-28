<?php

namespace App\Modules\GalleryCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GalleryCategory\Resources\GalleryCategoryCollection;
use App\Modules\GalleryCategory\Services\GalleryCategoryService;
use Illuminate\Http\Request;

class MainGalleryCategoryPaginateController extends Controller
{
    private $categoryService;

    public function __construct(GalleryCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function get(Request $request){
        $data = $this->categoryService->main_paginate($request->total ?? 10);
        return GalleryCategoryCollection::collection($data);
    }

}
