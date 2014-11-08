<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Http\Requests\CreateMenuRequest;
use Modules\Menu\Repositories\MenuRepository;

class MenuController extends AdminBaseController
{
    /**
     * @var MenuRepository
     */
    private $menu;
    /**
     * @var Request
     */
    private $request;

    public function __construct(MenuRepository $menu, Request $request)
    {
        parent::__construct();
        $this->menu = $menu;
        $this->request = $request;
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
        return $this->request->route('dashboard.menu.index');
    }

    public function edit(Menu $menu)
    {
        return View::make('menu::admin.menus.edit', compact('menu'));
    }
}
