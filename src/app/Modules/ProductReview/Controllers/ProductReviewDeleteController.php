<?php

namespace App\Modules\ProductReview\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductReview\Services\ProductReviewService;

class ProductReviewDeleteController extends Controller
{
    private $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->middleware('permission:delete products', ['only' => ['delete']]);
        $this->productReviewService = $productReviewService;
    }

    public function delete($product_id, $id){
        $productReview = $this->productReviewService->getById($product_id, $id);

        try {
            //code...
            $this->productReviewService->delete(
                $productReview
            );
            return response()->json([
                'message' => "Product Review deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
