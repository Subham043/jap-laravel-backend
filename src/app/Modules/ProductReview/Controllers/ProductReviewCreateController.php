<?php

namespace App\Modules\ProductReview\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ProductReview\Requests\ProductReviewCreateRequest;
use App\Modules\ProductReview\Resources\ProductReviewCollection;
use App\Modules\ProductReview\Services\ProductReviewService;

class ProductReviewCreateController extends Controller
{
    private $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->productReviewService = $productReviewService;
    }

    public function post(ProductReviewCreateRequest $request, $product_id){

        try {
            //code...
            $productReview = $this->productReviewService->create(
                [
                    ...$request->safe()->except(['image']),
                    'product_id' => $product_id,
                    'user_id' => auth()->user()->id,
                ]
            );

            if($request->hasFile('image')){
                $this->productReviewService->saveFile($productReview, 'image');
            }

            return response()->json([
                'message' => "Product Review created successfully.",
                'product_review' => ProductReviewCollection::make($productReview),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
