<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Http\Requests\CreateMenuRequest;
use Modules\Menu\Http\Requests\UpdateMenuRequest;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Menu\Repositories\MenuRepository;

class MenuController extends AdminBaseController
{
    /**
     * @var MenuRepository
     */
    private $menu;
    /**
     * @var Redirector
     */
    private $redirector;
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(MenuRepository $menu, MenuItemRepository $menuItem, Redirector $redirector)
    {
        parent::__construct();
        $this->menu = $menu;
        $this->redirector = $redirector;
        $this->menuItem = $menuItem;
    }

    public function index()
    {
        $menus = $this->menu->all();

        return View::make('menu::admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return View::make('menu::admin.menus.create');
    }

    public function store(CreateMenuRequest $request)
    {
        $this->menu->create($request->all());

        Flash::success('Menu created!');
        return $this->redirector->route('dashboard.menu.index');
    }

    public function edit(Menu $menu)
    {
        $menuItems = $this->menuItem->all();
        return View::make('menu::admin.menus.edit', compact('menu', 'menuItems'));
    }

    public function update(Menu $menu, UpdateMenuRequest $request)
    {
        $this->menu->update($menu, $request->all());

        Flash::success('Menu updated!');
        return $this->redirector->route('dashboard.menu.index');
    }

    public function destroy(Menu $menu)
    {
        $this->menu->destroy($menu);

        Flash::success('Menu destroyed');
        return $this->redirector->route('dashboard.menu.index');
    }
}
