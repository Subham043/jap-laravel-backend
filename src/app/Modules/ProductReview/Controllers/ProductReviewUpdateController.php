<?php

namespace App\Modules\ProductReview\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductReview\Requests\ProductReviewUpdateRequest;
use App\Modules\ProductReview\Resources\ProductReviewCollection;
use App\Modules\ProductReview\Services\ProductReviewService;

class ProductReviewUpdateController extends Controller
{
    private $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->middleware('permission:edit products', ['only' => ['post']]);
        $this->productReviewService = $productReviewService;
    }

    public function post(ProductReviewUpdateRequest $request, $product_id, $id){
        $productReview = $this->productReviewService->getById($product_id, $id);
        try {
            //code...
            $productReview = $this->productReviewService->update(
                [...$request->safe()->except(['image'])],
                $productReview
            );

            if($request->hasFile('image')){
                $this->productReviewService->saveFile($productReview, 'image');
            }

            return response()->json([
                'message' => "Product Review updated successfully.",
                'product_review' => ProductReviewCollection::make($productReview),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
