<?php

namespace Modules\Menu\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterMenuSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->weight(90);
            $group->item(trans('menu::menu.title'), function (Item $item) {
                $item->weight(30);
                $item->icon('fa fa-bars');
                $item->route('admin.menu.menu.index');
                $item->authorize(
                    $this->auth->hasAccess('menu.menus.index')
                );
            });
        });

        return $menu;
    }
}
