<?php namespace Modules\Menu\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Menu\Services\MenuService;

class MenuItemController
{
    /**
     * @var MenuService
     */
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $position => $item) {
            $this->menuService->handle($item, $position);
        }
    }
}
