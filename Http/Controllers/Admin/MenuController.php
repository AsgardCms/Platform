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
use Modules\Menu\Services\MenuRenderer;

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
    /**
     * @var MenuRenderer
     */
    private $menuRenderer;

    public function __construct(
        MenuRepository $menu,
        MenuItemRepository $menuItem,
        Redirector $redirector,
        MenuRenderer $menuRenderer
    ) {
        parent::__construct();
        $this->menu = $menu;
        $this->redirector = $redirector;
        $this->menuItem = $menuItem;
        $this->menuRenderer = $menuRenderer;
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

        Flash::success(trans('menu::messages.menu created'));
        return $this->redirector->route('dashboard.menu.index');
    }

    public function edit(Menu $menu)
    {
        $menuItems = $this->menuItem->rootsForMenu($menu->id);

        $menuStructure = $this->menuRenderer->renderForMenu($menu->id, $menuItems);

        return View::make('menu::admin.menus.edit', compact('menu', 'menuStructure'));
    }

    public function update(Menu $menu, UpdateMenuRequest $request)
    {
        $this->menu->update($menu, $request->all());

        Flash::success(trans('menu::messages.menu updated'));
        return $this->redirector->route('dashboard.menu.index');
    }

    public function destroy(Menu $menu)
    {
        $this->menu->destroy($menu);

        Flash::success(trans('menu::messages.menu deleted'));
        return $this->redirector->route('dashboard.menu.index');
    }
}
