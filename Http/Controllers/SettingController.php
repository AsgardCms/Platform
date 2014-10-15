<?php namespace Modules\Setting\Http\Controllers;

use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class SettingController extends AdminBaseController
{
    public function index()
    {
        return View::make('setting::admin.settings');
    }
}