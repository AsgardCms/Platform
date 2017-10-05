<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFolderRequest extends FormRequest
{
    public function rules()
    {
        $parentID = $this->get('parent_id');

        return [
            'name' => [
                'required',
                Rule::unique('media__files', 'filename')->where(function ($query) use ($parentID) {
                    return $query->where('is_folder', 1)->where('folder_id', $parentID);
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
