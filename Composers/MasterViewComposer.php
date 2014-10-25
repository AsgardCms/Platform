<?php namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Modules\Core\Contracts\Setting;

class MasterViewComposer
{
    /**
     * @var Setting
     */
    private $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function compose(View $view)
    {
        $view->with('sitename', $this->setting->get('core::site-name', App::getLocale()));
    }
}
