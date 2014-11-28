<?php namespace Modules\Core\Http\Controllers;

use Modules\Core\Contracts\Setting;

abstract class BasePublicController
{
    /**
     * @var string The active theme name
     */
    public $theme;
    /**
     * @var Setting
     */
    private $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
        $this->theme = $this->setting->get('core::template');
    }
}
