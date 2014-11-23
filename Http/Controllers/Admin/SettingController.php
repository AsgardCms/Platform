<?php namespace Modules\Setting\Http\Controllers\Admin;

use Illuminate\Session\Store;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repositories\SettingRepository;

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
        return Redirect::route('dashboard.module.settings', ['core']);
    }

    public function store(SettingRequest $request)
    {
        $this->setting->createOrUpdate($request->all());

        Flash::success('Settings saved!');
        return Redirect::route('dashboard.module.settings', [$this->session->get('module', 'Core')]);
    }

    public function getModuleSettings($currentModule)
    {
        $this->session->set('module', $currentModule);
        $modulesWithSettings = $this->setting->moduleSettings($this->module->enabled());

        $translatableSettings = $this->setting->translatableModuleSettings($currentModule);
        $plainSettings = $this->setting->plainModuleSettings($currentModule);

        $dbSettings = $this->setting->savedModuleSettings($currentModule);

        return View::make('setting::admin.module-settings',
            compact('currentModule', 'translatableSettings', 'plainSettings', 'dbSettings', 'modulesWithSettings'));
    }
}
