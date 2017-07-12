<?php

namespace Modules\Setting\Events;

use Modules\Setting\Entities\Setting;

class SettingIsUpdating
{
    private $settingName;
    private $settingValues;
    private $original;
    /**
     * @var Setting
     */
    private $setting;

    public function __construct(Setting $setting, $settingName, $settingValues)
    {
        $this->settingName = $settingName;
        $this->settingValues = $settingValues;
        $this->original = $settingValues;
        $this->setting = $setting;
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

    /**
     * @return Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }
}
