<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Modules\Setting\Contracts\Setting;

class SiteNameViewComposer
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
