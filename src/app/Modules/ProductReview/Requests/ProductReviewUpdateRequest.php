<?php

namespace App\Modules\ProductReview\Requests;

use App\Modules\ProductReview\Models\ProductReview;
use Illuminate\Support\Facades\Auth;


class ProductReviewUpdateRequest extends ProductReviewCreateRequest
{
    public function authorize()
    {
        $product_id = $this->route('product_id');
        return Auth::check() && ProductReview::with([
            'product' => function($q) use($product_id) {
                $q->where('id', $product_id);
            },
        ])->findOrFail($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'message' => 'required|string',
            'star' => 'required|numeric|gte:0|max:5',
            'image' => 'nullable|image|max:500',
        ];
    }
}
