<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules()
    {
        $role = $this->route('role');

        return [
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $role->id . ',id',
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
