<?php

namespace Modules\Media\Events;

class FileWasUnlinked
{
    /**
     * The imageable id
     * @var int
     */
    public $imageableId;

    /**
     * @param int $imageableId
     */
    public function __construct($imageableId)
    {
        $this->imageableId = $imageableId;
    }
}
