<?php namespace Modules\Menu\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Core\Internationalisation\Helper;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Repositories\MenuItemRepository;

class EloquentMenuItemRepository extends EloquentBaseRepository implements MenuItemRepository
{
    public function create($data)
    {
        $menuItem = new Menuitem;
        $menuItem->menu_id = $data['menu_id'];
        $menuItem->page_id = $data['page_id'];
        $menuItem->target = $data['target'];
        $menuItem->module_name = $data['module'];

        $translatableData = Helper::separateLanguages($data);
        Helper::updateTranslated($menuItem, $translatableData);

        return $menuItem;
    }

    public function update($menuItem, $data)
    {
        $menuItem->menu_id = $data['menu_id'];
        $menuItem->page_id = $data['page_id'];
        $menuItem->target = $data['target'];
        $menuItem->module_name = $data['module'];

        $translatableData = Helper::separateLanguages($data);
        Helper::updateTranslated($menuItem, $translatableData);

        return $menuItem;
    }

    public function roots()
    {
        return $this->model->roots()->orderBy('position')->get();
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
            $menuitemsArray[$menuitem->module_name][$menuitem->locale] = $menuitem->locale . '/' . $menuitem->uri;
        }

        return $menuitemsArray;
    }
}
