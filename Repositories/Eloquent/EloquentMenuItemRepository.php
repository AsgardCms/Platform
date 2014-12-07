<?php namespace Modules\Menu\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Repositories\MenuItemRepository;

class EloquentMenuItemRepository extends EloquentBaseRepository implements MenuItemRepository
{
    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($menuItem, $data)
    {
        $menuItem->update($data);

        return $menuItem;
    }

    public function rootsForMenu($menuId)
    {
        return $this->model->roots()->where('menu_id', $menuId)->orderBy('position')->get();
    }

    /**
     * Get Items to build routes
     *
     * @return Array
     */
    public function getForRoutes()
    {
        $menuitems = DB::table('menus')
            ->select(
                'primary',
                'menuitems.id',
                'menuitems.parent_id',
                'menuitems.module_name',
                'menuitem_translations.uri',
                'menuitem_translations.locale'
            )
            ->join('menuitems', 'menus.id', '=', 'menuitems.menu_id')
            ->join('menuitem_translations', 'menuitems.id', '=', 'menuitem_translations.menuitem_id')
            ->where('uri', '!=', '')
            ->where('module_name', '!=', '')
            ->where('status', '=', 1)
            ->where('primary', '=', 1)
            ->orderBy('module_name')
            ->get();

        $menuitemsArray = [];
        foreach ($menuitems as $menuitem) {
            $menuitemsArray[$menuitem->module_name][$menuitem->locale] = $menuitem->uri;
        }

        return $menuitemsArray;
    }
}
