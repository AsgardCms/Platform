<?php namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\Setting\Repositories\SettingRepository;

class MasterViewComposer
{
    /**
     * @var SettingRepository
     */
    private $setting;

    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }
    public function compose(View $view)
    {
        $view->with('sitename', $this->setting->findSettingForModule('site-name'));
    }
}
