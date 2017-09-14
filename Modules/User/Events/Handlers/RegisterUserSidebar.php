<?php

namespace Modules\User\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterUserSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('workshop::workshop.title'), function (Group $group) {
            $group->item(trans('user::users.title.users'), function (Item $item) {
                $item->weight(10);
                $item->icon('fa fa-users');
                $item->authorize(
                    $this->auth->hasAccess('user.users.index') or $this->auth->hasAccess('user.roles.index')
                );

                $item->item(trans('user::users.title.users'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('fa fa-users');
                    $item->route('admin.user.user.index');
                    $item->authorize(
                        $this->auth->hasAccess('user.users.index')
                    );
                });

                $item->item(trans('user::roles.title.roles'), function (Item $item) {
                    $item->weight(1);
                    $item->icon('fa fa-flag-o');
                    $item->route('admin.user.role.index');
                    $item->authorize(
                        $this->auth->hasAccess('user.roles.index')
                    );
                });
            });
        });
        $menu->group(trans('user::users.my account'), function (Group $group) {
            $group->weight(110);
            $group->item(trans('user::users.profile'), function (Item $item) {
                $item->weight(0);
                $item->icon('fa fa-user');
                $item->route('admin.account.profile.edit');
            });
            $group->item(trans('user::users.api-keys'), function (Item $item) {
                $item->weight(1);
                $item->icon('fa fa-key');
                $item->route('admin.account.api.index');
                $item->authorize(
                    $this->auth->hasAccess('account.api-keys.index')
                );
            });
        });

        return $menu;
    }
}
