<?php

namespace App\Modules\ProductImage\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductImage\Services\ProductImageService;

class ProductImageDeleteController extends Controller
{
    private $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->middleware('permission:delete products', ['only' => ['delete']]);
        $this->productImageService = $productImageService;
    }

    public function delete($product_id, $id){
        $productImage = $this->productImageService->getById($product_id, $id);

        try {
            //code...
            $this->productImageService->delete(
                $productImage
            );
            return response()->json([
                'message' => "Product Image deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
