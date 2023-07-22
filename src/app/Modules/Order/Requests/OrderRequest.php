<?php

namespace App\Modules\Order\Requests;

use App\Enums\PaymentMode;
use App\Enums\PaymentStatus;
use App\Modules\Cart\Services\CartService;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Order\Models\Order;
use App\Modules\Pincode\Models\Pincode;
use App\Modules\Product\Models\Product;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Validation\Rules\Enum;


class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cart = (new CartService)->get();
        return Auth::check() && $cart->products->count()!==0 && $cart->coupon->code == $this->coupon_code;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing_first_name' => ['required', 'string', 'max:255'],
            'billing_last_name' => ['required', 'string', 'max:255'],
            'billing_email' => ['required','email:rfc,dns'],
            'billing_phone' => ['required','numeric', 'digits:10'],
            'billing_country' => ['required', 'string', 'max:255'],
            'billing_state' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:255'],
            'billing_pin' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) {
                    $pincode = Pincode::where('pincode', $value)->first();
                    if (empty($pincode)) {
                        $fail("Products are not deliverable for the given pincode - {$value}.");
                    }
                },
            ],
            'billing_address_1' => ['required', 'string', 'max:255'],
            'billing_address_2' => ['nullable', 'string', 'max:255'],
            'shipping_first_name' => ['nullable', 'string', 'max:255'],
            'shipping_last_name' => ['nullable', 'string', 'max:255'],
            'shipping_email' => ['nullable','email:rfc,dns'],
            'shipping_phone' => ['nullable','numeric', 'digits:10'],
            'shipping_country' => ['nullable', 'string', 'max:255'],
            'shipping_state' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'string', 'max:255'],
            'shipping_pin' => ['nullable','numeric'],
            'shipping_address_1' => ['nullable', 'string', 'max:255'],
            'shipping_address_2' => ['nullable', 'string', 'max:255'],
            'order_notes' => ['nullable', 'string', 'max:255'],
            'mode_of_payment' => ['required', new Enum(PaymentMode::class)],
            'order' => ['required', 'array', 'min:1'],
            'order.*.product_id' => [
                'required_unless:order.*.quantity,0',
                'numeric',
                'gt:0',
                'exists:products,id',
                'min:1',
                function (string $attribute, mixed $value, Closure $fail) {
                    $product = Product::findOrFail($value);
                    $index = explode('.', $attribute)[1];
                    // if($product->pincodes->count()>0){
                    //     $availability = false;
                    //     foreach ($product->pincodes as $key => $value) {
                    //         # code...
                    //         if($this->billing_pin >= $value->min_pincode && $this->billing_pin <= $value->max_pincode){
                    //             $availability = true;
                    //             break;
                    //         }
                    //     }
                    //     if(!$availability){
                    //         $fail("The {$attribute} is not deliverable for the given pincode.");
                    //     }
                    // }

                    if ( empty($product->inventory) || $product->inventory == 0) {
                        $fail("The {$attribute} is out of stock.");
                    }
                    if ($product->inventory < $this->input("order.{$index}.quantity")) {
                        $fail("Requested quantity is more than the number of item in stock.");
                    }
                },
            ],
            'order.*.quantity' => [
                'required_unless:order.*.product_id,0',
                'numeric',
                'gt:0',
                'min:1',
            ],
            'coupon_code' => [
                'nullable',
                'string',
                'alpha_num:ascii',
                'max:500',
                'exists:coupons,code',
                function (string $attribute, mixed $value, Closure $fail) {
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
