<?php

namespace Modules\User\Events;

final class UserIsCreating
{
    /**
     * @var array
     */
    private $attributes;
    public $original;

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
        $this->attributes = array_merge($this->attributes, $attributes);
    }
}
