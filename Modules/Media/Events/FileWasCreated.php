<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\File;

class FileWasCreated
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
