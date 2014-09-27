<?php namespace Modules\Workshop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MigrateModuleRequest extends FormRequest
{
    public function rules()
    {
        return [];
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