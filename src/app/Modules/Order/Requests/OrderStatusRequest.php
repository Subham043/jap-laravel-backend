<?php

namespace App\Modules\Order\Requests;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Modules\Order\Services\OrderService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Validation\Rules\Enum;


class OrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && (new OrderService)->getById($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_status' => ['required', new Enum(OrderStatus::class), 'in:PROCESSING,CONFIRMED,OUT FOR DELIVERY,DELIVERED'],
            'payment_status' => ['required', new Enum(PaymentStatus::class), 'in:PENDING,PAID'],
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
