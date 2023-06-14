<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Resources\MainProductCollection;
use App\Modules\Product\Services\ProductService;

class MainProductDetailController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function get($slug){
        $product = $this->productService->getBySlug($slug);

        return response()->json([
            'product' => MainProductCollection::make($product),
        ], 200);
    }

}
