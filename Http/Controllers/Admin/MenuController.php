<?php namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Menu\Http\Requests\CreateMenuRequest;
use Modules\Menu\Repositories\MenuRepository;

class MenuController extends AdminBaseController
{
    /**
     * @var MenuRepository
     */
    private $menu;

    public function __construct(MenuRepository $menu)
    {
        parent::__construct();
        $this->menu = $menu;
    }

    public function index()
    {
        return View::make('menu::admin.menus.index');
    }

    public function create()
    {
        return View::make('menu::admin.menus.create');
    }

    public function store(CreateMenuRequest $request)
    {
        $this->menu->create($request->all());

        Flash::success('Menu created!');
        return Redirect::route('dashboard.menu.index');
    }
}
