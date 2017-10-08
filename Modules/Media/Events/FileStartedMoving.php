<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\File;

class FileStartedMoving
{
    /**
     * @var File
     */
    public $file;
    /**
     * @var array
     */
    public $previousData;

    public function __construct(File $file, array $previousData)
    {
        $this->file = $file;
        $this->previousData = $previousData;
    }
}
