<?php

namespace App\Modules\User\Requests;

use App\Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;


class UserUpdatePostRequest extends UserCreatePostRequest
{
    public function authorize()
    {
        return Auth::check() && User::findOrFail($this->route('id'));
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
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->route('id'),
            'role' => 'nullable|string|exists:Spatie\Permission\Models\Role,name',
            'phone' => ['required','numeric', 'digits:10', 'unique:users,phone,'.$this->route('id')],
            'confirm_password' => 'nullable|string|min:8|required_with:password|same:password',
            'password' => ['nullable',
                'string',
                Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
        ];
    }
}
