<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Http\Requests\CreateMenuItemRequest;
use Modules\Menu\Http\Requests\UpdateMenuItemRequest;
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
        $this->menuItem->create($this->addMenuId($menu, $request));

        Flash::success(trans('menu::messages.menuitem created'));
        return $this->redirector->route('dashboard.menu.edit', [$menu->id]);
    }

    public function edit(Menu $menu, Menuitem $menuItem)
    {
        return view('menu::admin.menuitems.edit', compact('menu', 'menuItem'));
    }

    public function update(Menu $menu, Menuitem $menuItem, UpdateMenuItemRequest $request)
    {
        $this->menuItem->update($menuItem, $this->addMenuId($menu, $request));

        Flash::success(trans('menu::messages.menuitem updated'));
        return $this->redirector->route('dashboard.menu.edit', [$menu->id]);
    }

    /**
     * @param Menu $menu
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return array
     */
    private function addMenuId(Menu $menu, FormRequest $request)
    {
        return array_merge($request->all(), ['menu_id' => $menu->id]);
    }
}
