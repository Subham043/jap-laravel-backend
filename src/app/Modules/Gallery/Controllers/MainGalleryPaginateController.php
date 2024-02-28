<?php

namespace App\Modules\Gallery\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Resources\MainGalleryCollection;
use App\Modules\Gallery\Services\GalleryService;
use Illuminate\Http\Request;

class MainGalleryPaginateController extends Controller
{
    private $productService;

    public function __construct(GalleryService $productService)
    {
        $this->productService = $productService;
    }

    public function get(Request $request){
        $data = $this->productService->main_paginate($request->total ?? 10);
        return MainGalleryCollection::collection($data);
    }

}
