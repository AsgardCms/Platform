<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Validators\MaxFolderSizeRule;

class UploadDropzoneMediaRequest extends FormRequest
{
    public function rules()
    {
        $extensions = 'mimes:' . str_replace('.', '', config('asgard.media.config.allowed-types'));
        $maxFileSize = $this->getMaxFileSizeInKilobytes();

        return [
            'file' => [
                'required',
                new MaxFolderSizeRule(),
                $extensions,
                "size:$maxFileSize",
            ],
        ];
    }

    public function messages()
    {
        $size = $this->getMaxFileSize();

        return [
            'file.size' => "File is too large. Must be below {$size}MB."
        ];
    }

    public function authorize()
    {
        return true;
    }

    private function getMaxFileSizeInKilobytes()
    {
        return $this->getMaxFileSize() * 1000;
    }

    private function getMaxFileSize()
    {
        return config('asgard.media.config.max-file-size');
    }
}
