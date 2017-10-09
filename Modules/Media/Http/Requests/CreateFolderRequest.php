<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Media\Validators\AlphaDashWithSpaces;

class CreateFolderRequest extends FormRequest
{
    public function rules()
    {
        $parentId = $this->get('parent_id');

        return [
            'name' => [
                new AlphaDashWithSpaces(),
                'required',
                Rule::unique('media__files', 'filename')->where(function ($query) use ($parentId) {
                    return $query->where('is_folder', 1)->where('folder_id', $parentId);
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
