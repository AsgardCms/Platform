<?php

namespace Modules\Page\Events\Handlers;

use Maatwebsite\Sidebar\Badge;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;
use Modules\Page\Repositories\PageRepository;

class RegisterPageSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('page::pages.pages'), function (Item $item) {
                $item->icon('fa fa-file');
                $item->weight(10);
                $item->route('admin.page.page.index');
                $item->badge(function (Badge $badge, PageRepository $pageRepository) {
                    $badge->setClass('bg-green');
                    $badge->setValue($pageRepository->countAll());
                });
                $item->authorize(
                    $this->auth->hasAccess('page.pages.index')
                );
            });
        });

        return $menu;
    }
}
