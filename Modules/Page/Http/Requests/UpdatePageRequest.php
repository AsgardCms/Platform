<?php

namespace Modules\Page\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePageRequest extends BaseFormRequest
{
    protected $translationsAttributesKey = 'page::pages.validation.attributes';

    public function rules()
    {
        return [
            'template' => 'required',
        ];
    }

    public function translationRules()
    {
        return [
            'title' => 'required',
            'slug' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'template.required' => trans('page::messages.template is required'),
            'is_home.unique' => trans('page::messages.only one homepage allowed'),
        ];
    }

    public function translationMessages()
    {
        return [
            'title.required' => trans('page::messages.title is required'),
            'slug.required' => trans('page::messages.slug is required'),
            'body.required' => trans('page::messages.body is required'),
        ];
    }
}
