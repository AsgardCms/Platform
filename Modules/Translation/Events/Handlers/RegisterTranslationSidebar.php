<?php

namespace Modules\Translation\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterTranslationSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
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
