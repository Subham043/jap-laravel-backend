<?php

namespace App\Modules\Cart\Requests;

use App\Enums\PaymentMode;
use App\Enums\PaymentStatus;
use App\Modules\Cart\Services\CartService;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Order\Models\Order;
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
                    if (empty($coupon)) {
                        $fail("The coupon code is invalid.");
                    }
                    if(!empty($coupon->maximum_number_of_use)){
                        $order = Order::where('user_id', auth()->user()->id)
                        ->where('coupon_id', $coupon->id)
                        ->where(function($q){
                            $q->where('mode_of_payment', PaymentMode::COD->value)
                            ->orWhere(function($query){
                                $query->where('mode_of_payment', PaymentMode::ONLINE->value)->where('payment_status', PaymentStatus::PAID->value);
                            });
                        })
                        ->get();
                        if ($order->count()>=$coupon->maximum_number_of_use) {
                            $fail("The coupon code has already been used {$coupon->maximum_number_of_use} times.");
                        }
                    }
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
