<?php

namespace App\Modules\Role\Requests;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleUpdatePostRequest extends RoleCreatePostRequest
{
    public function authorize()
    {
        return Auth::check() && Role::whereNot('name', 'Super-Admin')->findOrFail($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,'.$this->route('id'),
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|string|exists:Spatie\Permission\Models\Permission,name',
        ];
    }
}
