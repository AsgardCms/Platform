<?php namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        return View::make('core::admin.dashboard');
    }
}