<?php namespace Modules\User\Events;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class RegisterSidebarMenuItemEvent
{
    public $items;

    public function __construct()
    {
        $this->items = new Collection;

        $prefix = Config::get('core::core.admin-prefix');

        $this->items->put('user', Collection::make([
            [
                'weight' => '1',
                'request' => Request::is("{$prefix}/users*") or Request::is("{$prefix}/roles*"),
                'route' => '#',
                'icon-class' => 'fa fa-user',
                'title' => 'Users & Roles',
            ],
            [
                'request' => "{$prefix}/users*",
                'route' => 'dashboard.user.index',
                'icon-class' => 'fa fa-user',
                'title' => 'Users',
            ],
            [
                'request' => "{$prefix}/roles*",
                'route' => 'dashboard.role.index',
                'icon-class' => 'fa fa-flag-o',
                'title' => 'Roles',
            ]
        ]));
    }
}