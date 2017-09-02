<?php

namespace Modules\Workshop\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterWorkshopSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('workshop::workshop.title'), function (Group $group) {
            $group->weight(100);
            $group->authorize(
                $this->auth->hasAccess('workshop.sidebar.group')
            );
            $group->item(trans('workshop::workshop.modules'), function (Item $item) {
                $item->icon('fa fa-cogs');
                $item->weight(100);
                $item->route('admin.workshop.modules.index');
                $item->authorize(
                    $this->auth->hasAccess('workshop.modules.index')
                );
            });
            $group->item(trans('workshop::workshop.themes'), function (Item $item) {
                $item->icon('fa fa-cogs');
                $item->weight(101);
                $item->route('admin.workshop.themes.index');
                $item->authorize(
                    $this->auth->hasAccess('workshop.themes.index')
                );
            });
        });

        return $menu;
    }
}
