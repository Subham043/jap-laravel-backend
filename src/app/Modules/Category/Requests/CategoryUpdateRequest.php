<?php

namespace App\Modules\Category\Requests;

use App\Modules\Category\Models\Category;
use Illuminate\Support\Facades\Auth;


class CategoryUpdateRequest extends CategoryCreateRequest
{
    public function authorize()
    {
        return Auth::check() && Category::findOrFail($this->route('id'));
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
            'slug' => 'required|string|max:500|unique:categories,slug,'.$this->route('id'),
            'description' => 'required|string',
            'banner_image' => 'nullable|image|max:500',
            'icon_image' => 'nullable|image|max:500',
            'meta_title' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }
}
