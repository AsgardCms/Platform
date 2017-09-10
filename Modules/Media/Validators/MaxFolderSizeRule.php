<?php

namespace Modules\Media\Validators;

use Illuminate\Contracts\Validation\Rule;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MaxFolderSizeRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     * @param  string $attribute
     * @param  UploadedFile $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mediaPath = public_path(config('asgard.media.config.files-path'));
        $folderSize = $this->getDirSize($mediaPath);

        preg_match('/([0-9]+)/', $folderSize, $match);

        return ($match[0] + $value->getSize()) < config('asgard.media.config.max-total-size');
    }

    /**
     * Get the validation error message.
     * @return string
     */
    public function message()
    {
        $bytes = config('asgard.media.config.max-total-size');
        $size = $this->formatBytes($bytes);

        return trans('media::media.validation.max_size', ['size' => $size]);
    }

    /**
     * Get the directory size
     * @param string $directory
     * @return int
     */
    public function getDirSize($directory) : int
    {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = [
            trans('media::media.file-sizes.B'),
            trans('media::media.file-sizes.KB'),
            trans('media::media.file-sizes.MB'),
            trans('media::media.file-sizes.GB'),
            trans('media::media.file-sizes.TB'),
        ];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
