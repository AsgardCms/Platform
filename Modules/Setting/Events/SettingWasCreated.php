<?php

namespace Modules\Setting\Events;

use Modules\Media\Contracts\StoringMedia;
use Modules\Setting\Entities\Setting;

class SettingWasCreated implements StoringMedia
{
    /**
     * @var Setting
     */
    public $setting;

    /**
     * @var array
     */
    public $data;

    public function __construct(Setting $setting, $data)
    {
        $this->setting = $setting;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getEntity()
    {
        return $this->setting;
    }

    /**
     * @inheritDoc
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}
