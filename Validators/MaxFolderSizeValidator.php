<?php

namespace Modules\Media\Validators;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MaxFolderSizeValidator
{
    public function validateMaxSize($attribute, UploadedFile $value, $parameters)
    {
        $mediaPath = public_path(config('asgard.media.config.files-path'));
        $folderSize = $this->getDirSize($mediaPath);

        preg_match('/([0-9]+)/', $folderSize, $match);

        return ($match[0] + $value->getSize()) < config('asgard.media.config.max-total-size');
    }

    /**
    * Get the directory size
    * @param string $directory
    * @return int
    */
    public function getDirSize($directory)
    {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }
}
