<?php

namespace Modules\Translation\Sidebar;

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
        if (false === config('app.translations-gui')) {
            return $menu;
        }
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('translation::translations.title.translations'), function (Item $item) {
                $item->icon('fa fa-globe');
                $item->weight(10);
                $item->route('admin.translation.translation.index');
                $item->authorize(
                    $this->auth->hasAccess('translation.translations.index')
                );
            });
        });

        return $menu;
    }
}
