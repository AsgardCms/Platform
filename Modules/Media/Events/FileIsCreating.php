<?php

namespace Modules\Media\Events;

use Modules\Core\Contracts\EntityIsChanging;

final class FileIsCreating implements EntityIsChanging
{
    /**
     * Contains the attributes which can be changed by other listeners
     * @var array
     */
    private $attributes;
    /**
     * Contains the original attributes which cannot be changed
     * @var array
     */
    private $original;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
        $this->original = $attributes;
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
     * @return array
     */
    public function getOriginal()
    {
        return $this->original;
    }
}
