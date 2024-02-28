<?php

namespace App\Modules\Gallery\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Resources\GalleryCollection;
use App\Modules\Gallery\Services\GalleryService;

class GalleryDetailController extends Controller
{
    private $productService;

    public function __construct(GalleryService $productService)
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
        $this->productService = $productService;
    }

    public function get($id){
        $product = $this->productService->getById($id);

        return response()->json([
            'product' => GalleryCollection::make($product),
        ], 200);
    }

}
