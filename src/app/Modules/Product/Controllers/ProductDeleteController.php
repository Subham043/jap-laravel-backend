<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductService;

class ProductDeleteController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('permission:delete products', ['only' => ['delete']]);
        $this->productService = $productService;
    }

    public function delete($id){
        $product = $this->productService->getById($id);

        try {
            //code...
            $this->productService->delete(
                $product
            );
            return response()->json([
                'message' => "Product deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
