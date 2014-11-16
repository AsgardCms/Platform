<?php namespace Modules\Menu\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Menu\Services\MenuService;

class MenuItemController
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;
    /**
     * @var MenuService
     */
    private $menuService;

    public function __construct(MenuItemRepository $menuItem, MenuService $menuService)
    {
        $this->menuItem = $menuItem;
        $this->menuService = $menuService;
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $position => $item) {
            $this->menuService->handle($item, $position);
        }
    }
}
