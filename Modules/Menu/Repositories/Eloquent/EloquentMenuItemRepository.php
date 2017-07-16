<?php

namespace Modules\Menu\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Events\MenuItemIsCreating;
use Modules\Menu\Events\MenuItemIsUpdating;
use Modules\Menu\Events\MenuItemWasCreated;
use Modules\Menu\Events\MenuItemWasUpdated;
use Modules\Menu\Repositories\MenuItemRepository;

class EloquentMenuItemRepository extends EloquentBaseRepository implements MenuItemRepository
{
    public function create($data)
    {
        event($event = new MenuItemIsCreating($data));
        $menuItem = $this->model->create($event->getAttributes());

        event(new MenuItemWasCreated($menuItem));

        return $menuItem;
    }

    public function update($menuItem, $data)
    {
        event($event = new MenuItemIsUpdating($menuItem, $data));
        $menuItem->update($event->getAttributes());

        event(new MenuItemWasUpdated($menuItem));

        return $menuItem;
    }

    /**
     * Get online root elements
     *
     * @param  int    $menuId
     * @return object
     */
    public function rootsForMenu($menuId)
    {
        return $this->model->whereHas('translations', function (Builder $q) {
            $q->where('status', 1);
            $q->where('locale', App::getLocale());
        })->with('translations')->whereMenuId($menuId)->orderBy('position')->get();
    }

    /**
     * Get all root elements
     *
     * @param  int    $menuId
     * @return object
     */
    public function allRootsForMenu($menuId)
    {
        return $this->model->with('translations')->whereMenuId($menuId)->orderBy('parent_id')->orderBy('position')->get();
    }

    /**
     * Get Items to build routes
     *
     * @return Array
     */
    public function getForRoutes()
    {
        $menuitems = DB::table('menu__menus')
            ->select(
                'primary',
                'menu__menuitems.id',
                'menu__menuitems.parent_id',
                'menu__menuitems.module_name',
                'menu__menuitem_translations.uri',
                'menu__menuitem_translations.locale'
            )
            ->join('menu__menuitems', 'menu__menus.id', '=', 'menu__menuitems.menu_id')
            ->join('menu__menuitem_translations', 'menu__menuitems.id', '=', 'menu__menuitem_translations.menuitem_id')
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

    /**
     * Get the root menu item for the given menu id
     *
     * @param  int    $menuId
     * @return object
     */
    public function getRootForMenu($menuId)
    {
        return $this->model->with('translations')->where(['menu_id' => $menuId, 'is_root' => true])->firstOrFail();
    }

    /**
     * Return a complete tree for the given menu id
     *
     * @param  int    $menuId
     * @return object
     */
    public function getTreeForMenu($menuId)
    {
        $items = $this->rootsForMenu($menuId);

        return $items->nest();
    }

    /**
     * @param  string $uri
     * @param  string $locale
     * @return object
     */
    public function findByUriInLanguage($uri, $locale)
    {
        return $this->model->whereHas('translations', function (Builder $q) use ($locale, $uri) {
            $q->where('status', 1);
            $q->where('locale', $locale);
            $q->where('uri', $uri);
        })->with('translations')->first();
    }
}
