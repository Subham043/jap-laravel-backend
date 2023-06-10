<?php

namespace App\Modules\ProductReview\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductReview\Resources\ProductReviewCollection;
use App\Modules\ProductReview\Services\ProductReviewService;

class ProductReviewDetailController extends Controller
{
    private $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->middleware('permission:list products', ['only' => ['get']]);
        $this->productReviewService = $productReviewService;
    }

    public function get($product_id, $id){
        $productReview = $this->productReviewService->getById($product_id, $id);

        return response()->json([
            'product_review' => ProductReviewCollection::make($productReview),
        ], 200);
    }

}
