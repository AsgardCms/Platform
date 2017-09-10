<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Validators\MaxFolderSizeRule;

class UploadMediaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => ['required', new MaxFolderSizeRule()],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
