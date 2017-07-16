<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\File;

class FileWasUpdated
{
    /**
     * @var File
     */
    public $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
