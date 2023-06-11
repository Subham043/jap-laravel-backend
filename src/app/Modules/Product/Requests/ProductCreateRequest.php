<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Str;


class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
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
            'slug' => 'required|string|max:500|unique:products,slug',
            'description' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'discount' => 'required|numeric|gte:0',
            'featured_image' => 'required|image|max:500',
            'image_title' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'is_new_arrival' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_best_sale' => 'required|boolean',
            'category' => 'nullable|array|min:1',
            'category.*' => 'nullable|numeric|exists:categories,id',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->replace(
            Purify::clean(
                $this->all()
            )
        );
    }
}
