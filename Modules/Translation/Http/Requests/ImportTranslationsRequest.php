<?php

namespace Modules\Translation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportTranslationsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => ['required', 'extensions:csv'],
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'file.extensions' => trans('translation::translations.csv only allowed'),
        ];
    }
}
