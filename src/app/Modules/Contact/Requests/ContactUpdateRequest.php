<?php

namespace App\Modules\Contact\Requests;

use App\Modules\Contact\Models\Contact;
use Illuminate\Support\Facades\Auth;


class ContactUpdateRequest extends ContactCreateRequest
{
    public function authorize()
    {
        return Auth::check() && Contact::findOrFail($this->route('id'));
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
            'message' => 'nullable|string',
        ];
    }
}
