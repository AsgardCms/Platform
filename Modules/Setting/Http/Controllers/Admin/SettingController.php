<?php namespace Modules\Setting\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repositories\SettingRepository;
use Pingpong\Modules\Module;

class SettingController extends AdminBaseController
{
    /**
     * @var SettingRepository
     */
    private $setting;
    /**
     * @var Module
     */
    private $module;

    public function __construct(SettingRepository $setting, Module $module)
    {
        parent::__construct();

        $this->setting = $setting;
        $this->module = $module;
    }

    public function index()
    {
        $settings = $this->setting->all();

        $modulesWithSettings = $this->setting->moduleSettings($this->module->enabled());

        return View::make('setting::admin.settings', compact('settings', 'modulesWithSettings'));
    }

    public function store(SettingRequest $request)
    {
        $this->setting->createOrUpdate($request->all());

        Flash::success('Settings saved!');
        return Redirect::route('dashboard.setting.index');
    }

    public function getModuleSettings($module)
    {
        $moduleSettings = $this->setting->moduleSettings($module);

        $settings = $this->setting->savedModuleSettings($module);

        return View::make('setting::admin.module-settings', compact('module', 'moduleSettings', 'settings'));
    }
}
