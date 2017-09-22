<?php

namespace Modules\Workshop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Manager\ModuleManager;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Module;
use Symfony\Component\Console\Output\BufferedOutput;

class ModulesController extends AdminBaseController
{
    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var RepositoryInterface
     */
    private $modules;

    public function __construct(ModuleManager $moduleManager, RepositoryInterface $modules)
    {
        parent::__construct();

        $this->moduleManager = $moduleManager;
        $this->modules = $modules;
    }

    /**
     * Display a list of all modules
     * @return View
     */
    public function index()
    {
        $modules = $this->modules->all();

        return view('workshop::admin.modules.index', compact('modules'));
    }

    /**
     * Display module info
     * @param Module $module
     * @return View
     */
    public function show(Module $module)
    {
        $changelog = $this->moduleManager->changelogFor($module);

        return view('workshop::admin.modules.show', compact('module', 'changelog'));
    }

    /**
     * Disable the given module
     * @param Module $module
     * @return mixed
     */
    public function disable(Module $module)
    {
        if ($this->isCoreModule($module)) {
            return redirect()->route('admin.workshop.modules.show', [$module->getLowerName()])
                ->with('error', trans('workshop::modules.module cannot be disabled'));
        }

        $module->disable();

        return redirect()->route('admin.workshop.modules.show', [$module->getLowerName()])
            ->with('success', trans('workshop::modules.module disabled'));
    }

    /**
     * Enable the given module
     * @param Module $module
     * @return mixed
     */
    public function enable(Module $module)
    {
        $module->enable();

        return redirect()->route('admin.workshop.modules.show', [$module->getLowerName()])->with(
            'success',
            trans('workshop::modules.module enabled')
        );
    }

    /**
     * Update a given module
     * @param Request $request
     * @return Response json
     */
    public function update(Request $request)
    {
        $output = new BufferedOutput();
        Artisan::call('asgard:update', ['module' => $request->get('module')], $output);

        return Response::json(['updated' => true, 'message' => $output->fetch()]);
    }

    /**
     * Check if the given module is a core module that should be be disabled
     * @param Module $module
     * @return bool
     */
    private function isCoreModule(Module $module)
    {
        $coreModules = array_flip(config('asgard.core.config.CoreModules'));

        return isset($coreModules[$module->getLowerName()]);
    }
}
