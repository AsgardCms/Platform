<?php

namespace Modules\Translation\Events;

class TranslationNotFoundInCache
{
    /**
     * @var string The cache key
     */
    public $key;

    public function __construct($key)
    {
        $this->key = $key;
    }
}
