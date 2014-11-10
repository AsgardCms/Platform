<?php namespace Modules\Menu\Http\Controllers\Admin;

use Modules\Menu\Entities\Menu;

class MenuItemController
{
    public function create(Menu $menu)
    {
        return view('menu::admin.menuitems.create', compact('menu'));
    }

    public function store(Menu $menu)
    {
        dd('Form posted', $menu);
    }

    public function update(Menu $menu)
    {
    }
}
