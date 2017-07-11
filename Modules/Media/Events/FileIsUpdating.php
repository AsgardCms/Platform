<?php

namespace Modules\Media\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Media\Entities\File;

final class FileIsUpdating implements EntityIsChanging
{
    /**
     * @var File
     */
    private $file;
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var array
     */
    private $original;

    public function __construct(File $file, array $data)
    {
        $this->file = $file;
        $this->attributes = $data;
        $this->original = $data;
    }

    /**
     * @return array
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_replace_recursive($this->attributes, $attributes);
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
