<?php

namespace App\Modules\ProductImage\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductImage\Resources\ProductImageCollection;
use App\Modules\ProductImage\Services\ProductImageService;

class ProductImageDetailController extends Controller
{
    private $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
        $this->productImageService = $productImageService;
    }

    public function get($product_id, $id){
        $productImage = $this->productImageService->getById($product_id, $id);

        return response()->json([
            'product_image' => ProductImageCollection::make($productImage),
        ], 200);
    }

}
