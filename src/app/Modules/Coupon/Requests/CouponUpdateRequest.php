<?php

namespace App\Modules\Coupon\Requests;

use App\Modules\Coupon\Models\Coupon;
use Illuminate\Support\Facades\Auth;


class CouponUpdateRequest extends CouponCreateRequest
{
    public function authorize()
    {
        return Auth::check() && Coupon::findOrFail($this->route('id'));
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
            'code' => 'required|string|alpha_num:ascii|max:500|unique:coupons,code,'.$this->route('id'),
            'description' => 'required|string',
            'discount' => 'required|numeric|gt:0',
            'maximum_dicount_in_price' => 'nullable|numeric|gt:0',
            'maximum_number_of_use' => 'nullable|numeric|gt:0',
            'is_active' => 'required|boolean',
        ];
    }
}
