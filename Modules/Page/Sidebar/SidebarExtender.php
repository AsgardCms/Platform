<?php

namespace Modules\Page\Sidebar;

use Maatwebsite\Sidebar\Badge;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Page\Repositories\PageRepository;
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
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('page::pages.title.pages'), function (Item $item) {
                $item->icon('fa fa-file');
                $item->weight(1);
                $item->route('admin.page.page.index');
                $item->badge(function (Badge $badge, PageRepository $page) {
                    $badge->setClass('bg-green');
                    $badge->setValue($page->countAll());
                });
                $item->authorize(
                    $this->auth->hasAccess('page.pages.index')
                );
            });

            $group->authorize(
                $this->auth->hasAccess('page.*')
            );
        });

        return $menu;
    }
}
