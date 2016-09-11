<?php

namespace Modules\Setting\Events;

class SettingWasCreated
{
    /**
     * @var bool
     */
    public $isTranslatable;
    /**
     * @var string Setting name
     */
    public $name;
    /**
     * @var string|array
     */
    public $values;

    /**
     * @param $name
     * @param $isTranslatable
     * @param $values
     */
    public function __construct($name, $isTranslatable, $values)
    {
        $this->isTranslatable = $isTranslatable;
        $this->name = $name;
        $this->values = $values;
    }
}
