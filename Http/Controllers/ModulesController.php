<?php namespace Modules\Workshop\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Http\Requests\ModulesRequest;
use Modules\Workshop\Manager\ModuleManager;

class ModulesController extends AdminBaseController
{
    /**
     * @var ModuleManager
     */
    private $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        parent::__construct();

        $this->moduleManager = $moduleManager;
    }

    public function index()
    {
        $modules = $this->moduleManager->all();
        $coreModules = $this->moduleManager->getCoreModules();

        return View::make('workshop::admin.modules.index', compact('modules', 'coreModules'));
    }

    public function store(ModulesRequest $request)
    {
        $enabledModules = $this->moduleManager->getFlippedEnabledModules();

        $modules = $request->modules;
        foreach ($modules as $module => $value) {
            if (isset($enabledModules[$module])) {
                unset($enabledModules[$module]);
                unset($modules[$module]);
            }
        }
        $this->moduleManager->disableModules($enabledModules);
        $this->moduleManager->enableModules($modules);

        Flash::success('Modules configuration saved!');
        return Redirect::route('dashboard.modules.index');
    }
}
