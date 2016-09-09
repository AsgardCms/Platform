<?php

namespace Modules\Tag\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdateTagRequest extends BaseFormRequest
{
    public function translationRules()
    {
        return [
            'slug' => 'required',
            'name' => 'required',
        ];
    }

    public function rules()
    {
        return [
            'namespace' => 'required',
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
