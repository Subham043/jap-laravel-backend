<?php

namespace App\Modules\Cart\Requests;

use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Closure;


class CartRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => ['nullable', 'array', 'min:1'],
            'data.*.product_id' => [
                'required_unless:data.*.quantity,0',
                'numeric',
                'gt:0',
                'exists:products,id',
                'min:1',
                function (string $attribute, mixed $value, Closure $fail) {
                    $product = Product::findOrFail($value);
                    if ( empty($product->inventory) ||$product->inventory == 0) {
                        $fail("The {$attribute} is out of stock.");
                    }
                },
            ],
            'data.*.quantity' => ['required_unless:data.*.product_id,0','numeric', 'gt:0', 'min:1'],
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
