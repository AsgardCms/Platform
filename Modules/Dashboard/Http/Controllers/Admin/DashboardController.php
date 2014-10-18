<?php namespace Modules\Dashboard\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class DashboardController extends AdminBaseController
{
    public function index()
    {
        return View::make('dashboard::admin.dashboard');
    }
}
