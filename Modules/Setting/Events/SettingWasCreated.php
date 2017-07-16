<?php

namespace Modules\Setting\Events;

use Modules\Setting\Entities\Setting;

class SettingWasCreated
{
    /**
     * @var Setting
     */
    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
