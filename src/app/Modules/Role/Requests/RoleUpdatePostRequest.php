<?php

namespace App\Modules\Role\Requests;

class RoleUpdatePostRequest extends RoleCreatePostRequest
{

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
