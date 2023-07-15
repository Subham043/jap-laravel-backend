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
            'company_name' => 'required|string|max:255',
            'company_website' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity' => 'required|string|max:255',
            'gst' => 'required|string|max:255',
            'certification' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'alternate_name' => 'nullable|string|max:255',
            'alternate_phone' => 'nullable|numeric|digits:10',
            'alternate_email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
        ];
    }
}
