<?php

namespace App\Modules\ProductImage\Requests;

use App\Modules\ProductImage\Models\ProductImage;
use Illuminate\Support\Facades\Auth;


class ProductImageUpdateRequest extends ProductImageCreateRequest
{
    public function authorize()
    {
        $product_id = $this->route('product_id');
        return Auth::check() && ProductImage::with([
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
            'image' => 'nullable|image|max:500',
            'image_title' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:500',
        ];
    }
}
