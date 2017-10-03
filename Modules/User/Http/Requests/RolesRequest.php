<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
{
    public function rules()
    {
        $roleID = $this->route('roles');

        return [
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $roleID . ',id',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }
}
