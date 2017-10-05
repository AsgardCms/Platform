<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFolderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('media__files', 'filename')->where(function ($query) {
                    return $query->where('is_folder', 1);
                }),
            ],
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
