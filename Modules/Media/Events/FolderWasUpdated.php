<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\File;

class FolderWasUpdated
{
    /**
     * @var File
     */
    public $folder;
    /**
     * @var array
     */
    public $data;

    public function __construct(File $folder, array $data)
    {
        $this->folder = $folder;
        $this->data = $data;
    }
}
