<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Validators\MaxFolderSizeRule;

class UploadMediaRequest extends FormRequest
{
    public function rules()
    {
        $extensions = 'mimes:' . str_replace('.', '', config('asgard.media.config.allowed-types'));

        return [
            'file' => ['required', new MaxFolderSizeRule(), $extensions],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
