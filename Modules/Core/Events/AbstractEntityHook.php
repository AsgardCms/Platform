<?php

namespace Modules\Core\Events;

abstract class AbstractEntityHook
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
     * @param string $attribute
     * @param null $default
     * @return string|null
     */
    public function getAttribute($attribute, $default = null)
    {
        return data_get($this->attributes, $attribute, $default);
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_replace_recursive($this->attributes, $attributes);
    }

    /**
     * @param string|null $key
     * @param string|null $default
     * @return array
     */
    public function getOriginal($key = null, $default = null)
    {
        if ($key !== null) {
            return data_get($this->original, $key, $default);
        }

        return $this->original;
    }
}
