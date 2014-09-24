<?php namespace Modules\User\Composers;

use Illuminate\Support\Collection;

class SidebarViewComposer
{
    public function compose($view)
    {
        $view->items->put('user', Collection::make([
            [
                'weight' => '1',
                'request' => "{$view->prefix}/users*",
                'route' => '#',
                'icon-class' => 'fa fa-user',
                'title' => 'Users & Roles',
            ],
            [
                'request' => "{$view->prefix}/users",
                'route' => 'dashboard.user.index',
                'icon-class' => 'fa fa-user',
                'title' => 'Users',
            ],
            [
                'request' => "{$view->prefix}/messages/roles",
                'route' => 'dashboard.role.index',
                'icon-class' => 'fa fa-flag-o',
                'title' => 'Roles',
            ]
        ]));
    }
}