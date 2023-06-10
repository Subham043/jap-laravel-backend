<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Resources\ProductCollection;
use App\Modules\Product\Services\ProductService;

class ProductDetailController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
        $this->productService = $productService;
    }

    public function get($id){
        $product = $this->productService->getById($id);

        return response()->json([
            'product' => ProductCollection::make($product),
        ], 200);
    }

}
