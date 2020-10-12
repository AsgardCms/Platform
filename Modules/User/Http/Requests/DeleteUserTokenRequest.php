<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserTokenRequest extends FormRequest
{
    public function rules()
    {
        return [];
    }

    public function authorize()
    {
        return ($this->userTokenId->user->id === $this->user()->id);
    }

    public function messages()
    {
        return [];
    }
}
