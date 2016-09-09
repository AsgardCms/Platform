<?php

namespace Modules\Menu\Http\Controllers\Api;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Menu\Services\MenuOrdener;

class MenuItemController extends Controller
{
    /**
     * @var Repository
     */
    private $cache;
    /**
     * @var MenuOrdener
     */
    private $menuOrdener;
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(MenuOrdener $menuOrdener, Repository $cache, MenuItemRepository $menuItem)
    {
        $this->cache = $cache;
        $this->menuOrdener = $menuOrdener;
        $this->menuItem = $menuItem;
    }

    /**
     * Update all menu items
     * @param Request $request
     */
    public function update(Request $request)
    {
        $this->cache->tags('menuItems')->flush();

        $this->menuOrdener->handle($request->get('menu'));
    }

    /**
     * Delete a menu item
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $menuItem = $this->menuItem->find($request->get('menuitem'));

        if (! $menuItem) {
            return Response::json(['errors' => true]);
        }

        $this->menuItem->destroy($menuItem);

        return Response::json(['errors' => false]);
    }
}
