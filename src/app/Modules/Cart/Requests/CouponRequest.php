<?php

namespace App\Modules\Cart\Requests;

use App\Modules\Cart\Services\CartService;
use App\Modules\Coupon\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Closure;


class CouponRequest extends FormRequest
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
            'coupon_code' => [
                'required',
                'string',
                'alpha_num:ascii',
                'max:500',
                'exists:coupons,code',
                function (string $attribute, mixed $value, Closure $fail) {
                    $cart = (new CartService)->get();
                    if($cart->products->count()===0){
                        $fail("Please add products to your cart in order to use coupon.");
                    }
                    $coupon = Coupon::where('code', $value)->first();
                    // if ( empty($product->inventory) ||$product->inventory == 0) {
                    //     $fail("The {$attribute} is out of stock.");
                    // }
                },
            ],
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
