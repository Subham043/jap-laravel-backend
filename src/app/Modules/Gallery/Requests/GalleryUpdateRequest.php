<?php

namespace App\Modules\Gallery\Requests;

use App\Modules\Gallery\Models\Gallery;
use Illuminate\Support\Facades\Auth;


class GalleryUpdateRequest extends GalleryCreateRequest
{
    public function authorize()
    {
        return Auth::check() && Gallery::findOrFail($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:500',
            'image_title' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:500',
            'category' => 'nullable|numeric|exists:gallery_categories,id',
        ];
    }
}
