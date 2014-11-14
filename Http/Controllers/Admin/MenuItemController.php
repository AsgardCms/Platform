<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Http\Requests\CreateMenuItemRequest;
use Modules\Menu\Repositories\MenuItemRepository;

class MenuItemController
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;
    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(MenuItemRepository $menuItem, Redirector $redirector)
    {
        $this->menuItem = $menuItem;
        $this->redirector = $redirector;
    }

    public function create(Menu $menu)
    {
        return view('menu::admin.menuitems.create', compact('menu'));
    }

    public function store(Menu $menu, CreateMenuItemRequest $request)
    {
        $this->menuItem->create(array_merge($request->all(), ['menu_id' => $menu->id]));

        Flash::success('Menu item created!');
        return $this->redirector->route('dashboard.menu.edit', [$menu->id]);
    }

    public function edit(Menu $menu, Menuitem $menuitem)
    {
        dd('edit form');
    }

    public function update(Menu $menu)
    {
    }
}
