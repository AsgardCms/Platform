<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class MenuController extends AdminBaseController
{
    public function index()
    {
        return View::make('menu::admin.menus.index');
    }

    public function create()
    {
        return View::make('menu::admin.menus.create');
    }

    public function store()
    {
        dd('form posted');
    }
}
