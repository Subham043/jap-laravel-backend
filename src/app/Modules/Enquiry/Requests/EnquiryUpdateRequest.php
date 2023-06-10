<?php

namespace App\Modules\Enquiry\Requests;

use App\Modules\Enquiry\Models\Enquiry;
use Illuminate\Support\Facades\Auth;


class EnquiryUpdateRequest extends EnquiryCreateRequest
{
    public function authorize()
    {
        return Auth::check() && Enquiry::findOrFail($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric|digits:10',
            'message' => 'required|string',
        ];
    }
}
