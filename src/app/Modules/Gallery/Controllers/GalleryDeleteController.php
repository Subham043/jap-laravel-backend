<?php

namespace App\Modules\Gallery\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Services\GalleryService;

class GalleryDeleteController extends Controller
{
    private $productService;

    public function __construct(GalleryService $productService)
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
                'message' => "Gallery deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
