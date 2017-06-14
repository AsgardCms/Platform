<?php

namespace Modules\Setting\Http\Controllers\Admin;

use Illuminate\Session\Store;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Core\Traits\CanRequireAssets;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repositories\SettingRepository;
use Nwidart\Modules\Module;

class SettingController extends AdminBaseController
{
    use CanRequireAssets;
    /**
     * @var SettingRepository
     */
    private $setting;
    /**
     * @var Module
     */
    private $module;
    /**
     * @var Store
     */
    private $session;

    public function __construct(SettingRepository $setting, Store $session)
    {
        parent::__construct();

        $this->setting = $setting;
        $this->module = app('modules');
        $this->session = $session;
    }

    public function index()
    {
        return redirect()->route('dashboard.module.settings', ['core']);
    }

    public function store(SettingRequest $request)
    {
        $this->setting->createOrUpdate($request->all());

        return redirect()->route('dashboard.module.settings', [$this->session->get('module', 'Core')])
            ->withSuccess(trans('setting::messages.settings saved'));
    }

    public function getModuleSettings(Module $currentModule)
    {
        $this->session->put('module', $currentModule->getLowerName());

        $modulesWithSettings = $this->setting->moduleSettings($this->module->enabled());

        $translatableSettings = $this->setting->translatableModuleSettings($currentModule->getLowerName());
        $plainSettings = $this->setting->plainModuleSettings($currentModule->getLowerName());

        $dbSettings = $this->setting->savedModuleSettings($currentModule->getLowerName());

        return view('setting::admin.module-settings',
            compact('currentModule', 'translatableSettings', 'plainSettings', 'dbSettings', 'modulesWithSettings'));
    }
}
