<?php namespace Modules\Workshop\Http\Controllers;

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
        $this->beforeFilter('permissions');
    }

    public function index()
    {
        return View::make('workshop::admin.workbench.index');
    }

    public function generate(GenerateModuleRequest $request)
    {
        $output = new BufferedOutput;
        Artisan::call('module:make', ['name' => $request->name], $output);

        Flash::message($output->fetch());
        return Redirect::route('dashboard.workbench.index');
    }

    public function migrate(MigrateModuleRequest $request)
    {
        $output = new BufferedOutput;
        Artisan::call('module:migrate', ['module' => $request->module], $output);

        Flash::message($output->fetch());
        return Redirect::route('dashboard.workbench.index');
    }

    public function install(InstallModuleRequest $request)
    {
        $output = new BufferedOutput;
        $arguments['name'] = $request->vendorName;
        if ($request->subtree) {
            $arguments['--tree'] = '';
        }
        Artisan::call('module:install', $arguments, $output);

        Flash::message($output->fetch());
        return Redirect::route('dashboard.workbench.index');
    }

    public function seed(SeedModuleRequest $request)
    {
        $output = new BufferedOutput;
        Artisan::call('module:seed', ['module' => $request->module], $output);

        Flash::message($output->fetch());
        return Redirect::route('dashboard.workbench.index');
    }
}