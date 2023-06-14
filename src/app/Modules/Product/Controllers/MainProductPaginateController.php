<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Resources\MainProductCollection;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\Request;

class MainProductPaginateController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function get(Request $request){
        $data = $this->productService->main_paginate($request->total ?? 10);
        return MainProductCollection::collection($data);
    }

}
