<?php namespace Modules\Menu\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Repositories\MenuItemRepository;

class EloquentMenuItemRepository extends EloquentBaseRepository implements MenuItemRepository
{
    public function create($data)
    {
        dd($data);
        $menuItem = new Menuitem;
        $menuItem->menu_id = $data['menu_id'];
        $menuItem->page_id = $data['page_id'];
        $menuItem->target = $data['target'];
        $menuItem->module_name = $data['module_name'];

        unset($data['menu_id'], $data['page_id'], $data['target'], $data['module_name']);

        $translatableData = Helper::separateLanguages($data);
        Helper::updateTranslated($menuItem, $translatableData);

        return $menuItem;
    }
}
