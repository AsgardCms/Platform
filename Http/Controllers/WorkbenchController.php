<?php namespace Modules\Workshop\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Http\Requests\GenerateModuleRequest;
use Symfony\Component\Console\Output\BufferedOutput;

class WorkbenchController extends AdminBaseController
{
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
}