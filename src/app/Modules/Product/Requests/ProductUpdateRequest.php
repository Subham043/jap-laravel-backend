<?php

namespace App\Modules\Product\Requests;

use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;


class ProductUpdateRequest extends ProductCreateRequest
{
    public function authorize()
    {
        return Auth::check() && Product::findOrFail($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:500',
            'slug' => 'required|string|max:500|unique:products,slug,'.$this->route('id'),
            'description' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'discount' => 'required|numeric|gte:0',
            'featured_image' => 'nullable|image|max:500',
            'image_title' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'is_active' => 'required|boolean',
            'is_new_arrival' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_best_sale' => 'required|boolean',
            'category' => 'nullable|array|min:1',
            'category.*' => 'nullable|numeric|exists:categories,id',
        ];
    }
}
