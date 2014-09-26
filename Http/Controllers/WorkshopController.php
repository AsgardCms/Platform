<?php namespace Modules\Workshop\Http\Controllers;

use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Pingpong\Modules\Module;

class WorkshopController extends AdminBaseController
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
}