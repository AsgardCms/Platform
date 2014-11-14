<?php namespace Modules\Menu\Http\Controllers\Admin;

use Modules\Menu\Entities\Menu;
use Modules\Menu\Http\Requests\CreateMenuItemRequest;

class MenuItemController
{
    public function create(Menu $menu)
    {
        return view('menu::admin.menuitems.create', compact('menu'));
    }

    public function store(Menu $menu, CreateMenuItemRequest $request)
    {
        dd('Form posted', $menu, $request->all());
    }

    public function update(Menu $menu)
    {
    }
}
