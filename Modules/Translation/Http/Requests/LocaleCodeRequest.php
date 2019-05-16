<?php

namespace Modules\Translation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocaleCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_by' => ['required', 'string'],
            'order'    => ['required', 'string', 'in:asc,desc'],
            'search'   => ['nullable', 'string', 'min:3'],
        ];
    }
}
