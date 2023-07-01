<?php

namespace App\Modules\Product\Requests;

use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class ProductPincodeRequest extends FormRequest
{
    public function authorize()
    {
        return Product::where('slug', $this->route('slug'))->where('is_active', true)->firstOrFail();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pincode' => 'required|numeric|gte:0',
        ];
    }
}
