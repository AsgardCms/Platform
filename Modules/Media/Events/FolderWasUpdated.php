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
    /**
     * @var array
     */
    public $previousFolderData;

    public function __construct(File $folder, array $data, array $previousFolderData)
    {
        $this->folder = $folder;
        $this->data = $data;
        $this->previousFolderData = $previousFolderData;
    }
}
