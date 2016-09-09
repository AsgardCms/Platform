<?php

namespace Modules\Workshop\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\User\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('workshop::workshop.title'), function (Group $group) {
            $group->weight(100);
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

            $group->authorize(
                $this->auth->hasAccess('workshop.*') or $this->auth->hasAccess('user.*') or $this->auth->hasAccess('setting.*')
            );
        });

        return $menu;
    }
}
