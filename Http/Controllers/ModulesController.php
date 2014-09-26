<?php namespace Modules\Workshop\Http\Controllers;

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

        return View::make('workshop::admin.modules.index', compact('modules'));
    }

    public function store(ModulesRequest $request)
    {
        $enabledModules = $this->module->enabled();
        $enabledModules = array_flip($enabledModules);

        $modules = $request->modules;
        foreach ($modules as $module => $value) {
            if (isset($enabledModules[$module])) {
                unset($enabledModules[$module]);
                unset($modules[$module]);
            }
        }
        // Disabled not needed modules
        foreach ($enabledModules as $moduleToDisable => $value) {
            $this->module->disable($moduleToDisable);
        }
        // Enable new modules
        foreach ($modules as $moduleToEnable => $value) {
            $this->module->enable($moduleToEnable);
        }

        Flash::success('Modules configuration saved!');
        return Redirect::route('dashboard.modules.index');
    }
}