<?php namespace Modules\Workshop\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Http\Requests\ModulesRequest;
use Pingpong\Modules\Module;

class ModulesController extends AdminBaseController
{
    /**
     * @var Module
     */
    private $module;

    public function __construct(Module $module)
    {
        parent::__construct();

        $this->module = $module;
    }
    public function index()
    {
        $modules = $this->module->all();
        $coreModules = $this->getCoreModules();

        return View::make('workshop::admin.modules.index', compact('modules', 'coreModules'));
    }

    public function store(ModulesRequest $request)
    {
        $enabledModules = $this->getFlippedEnabledModules();

        $modules = $request->modules;
        foreach ($modules as $module => $value) {
            if (isset($enabledModules[$module])) {
                unset($enabledModules[$module]);
                unset($modules[$module]);
            }
        }
        // Disabled not needed modules
        $this->disableModules($enabledModules);
        // Enable new modules
        $this->enableModules($modules);

        Flash::success('Modules configuration saved!');
        return Redirect::route('dashboard.modules.index');
    }

    /**
     * @return array
     */
    private function getCoreModules()
    {
        $coreModules = Config::get('core::config.CoreModules');
        $coreModules = array_flip($coreModules);
        return $coreModules;
    }

    /**
     * @return array
     */
    private function getFlippedEnabledModules()
    {
        $enabledModules = $this->module->enabled();
        $enabledModules = array_flip($enabledModules);
        return $enabledModules;
    }

    private function disableModules($enabledModules)
    {
        $coreModules = $this->getCoreModules();

        foreach ($enabledModules as $moduleToDisable => $value) {
            if (isset($coreModules[$moduleToDisable])) {
                continue;
            }
            $this->module->disable($moduleToDisable);
        }
    }

    /**
     * @param $modules
     */
    private function enableModules($modules)
    {
        foreach ($modules as $moduleToEnable => $value) {
            $this->module->enable($moduleToEnable);
        }
    }
}