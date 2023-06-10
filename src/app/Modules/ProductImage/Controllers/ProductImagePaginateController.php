<?php

namespace App\Modules\ProductImage\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductImage\Resources\ProductImageCollection;
use App\Modules\ProductImage\Services\ProductImageService;
use Illuminate\Http\Request;

class ProductImagePaginateController extends Controller
{
    private $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
        $this->productImageService = $productImageService;
    }

    public function get(Request $request, $product_id){
        $data = $this->productImageService->paginate($request->total ?? 10, $product_id);
        return ProductImageCollection::collection($data);
    }

}
