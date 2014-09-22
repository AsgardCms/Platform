<?php namespace Modules\Dashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        return View::make('dashboard::admin.dashboard');
    }
}