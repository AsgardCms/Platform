<?php

namespace Modules\Workshop\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Http\Requests\GenerateModuleRequest;
use Modules\Workshop\Http\Requests\InstallModuleRequest;
use Modules\Workshop\Http\Requests\MigrateModuleRequest;
use Modules\Workshop\Http\Requests\SeedModuleRequest;
use Symfony\Component\Console\Output\BufferedOutput;

class WorkbenchController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the index view
     * @return mixed
     */
    public function index()
    {
        return View::make('workshop::admin.workbench.index');
    }

    /**
     * Generate a module given its name
     * @param  GenerateModuleRequest $request
     * @return mixed
     */
    public function generate(GenerateModuleRequest $request)
    {
        $output = new BufferedOutput();
        Artisan::call('module:make', ['name' => $request->name], $output);

        Flash::message($output->fetch());

        return Redirect::route('admin.workshop.workbench.index');
    }

    /**
     * Run the migration for the given module
     * @param  MigrateModuleRequest $request
     * @return mixed
     */
    public function migrate(MigrateModuleRequest $request)
    {
        $output = new BufferedOutput();
        Artisan::call('module:migrate', ['module' => $request->module], $output);

        Flash::message($output->fetch());

        return Redirect::route('admin.workshop.workbench.index');
    }

    /**
     * Run the install command for the given vendor/module
     * @param  InstallModuleRequest $request
     * @return mixed
     */
    public function install(InstallModuleRequest $request)
    {
        $output = new BufferedOutput();
        $arguments = [];
        $arguments['name'] = $request->vendorName;
        if ($request->subtree) {
            $arguments['--tree'] = '';
        }
        Artisan::call('module:install', $arguments, $output);

        Flash::message($output->fetch());

        return Redirect::route('admin.workshop.workbench.index');
    }

    /**
     * Run the seed command for the given module
     * @param  SeedModuleRequest $request
     * @return mixed
     */
    public function seed(SeedModuleRequest $request)
    {
        $output = new BufferedOutput();
        Artisan::call('module:seed', ['module' => $request->module], $output);

        Flash::message($output->fetch());

        return Redirect::route('admin.workshop.workbench.index');
    }
}
