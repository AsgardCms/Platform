<?php

namespace Modules\Setting\Events;

class SettingIsCreating
{
    private $settingName;
    private $settingValues;
    private $original;

    public function __construct($settingName, $settingValues)
    {
        $this->settingName = $settingName;
        $this->settingValues = $settingValues;
        $this->original = $settingValues;
    }

    /**
     * @return mixed
     */
    public function getSettingName()
    {
        return $this->settingName;
    }

    /**
     * @return mixed
     */
    public function getSettingValues()
    {
        return $this->settingValues;
    }

    /**
     * @param mixed $settingValues
     */
    public function setSettingValues($settingValues)
    {
        $this->settingValues = $settingValues;
    }

    /**
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }
}
