<?php namespace Modules\User\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('user', Collection::make([
            [
                'weight' => '1',
                'request' => Request::is("*/{$view->prefix}/users*") or Request::is("*/{$view->prefix}/roles*"),
                'route' => '#',
                'icon-class' => 'fa fa-user',
                'title' => 'Users & Roles',
                'permission' => $this->auth->hasAccess('users.index') or $this->auth->hasAccess('roles.index')
            ],
            [
                'request' => "*/{$view->prefix}/users*",
                'route' => 'dashboard.user.index',
                'icon-class' => 'fa fa-user',
                'title' => 'Users',
                'permission' => $this->auth->hasAccess('users.index')
            ],
            [
                'request' => "*/{$view->prefix}/roles*",
                'route' => 'dashboard.role.index',
                'icon-class' => 'fa fa-flag-o',
                'title' => 'Roles',
                'permission' => $this->auth->hasAccess('roles.index')
            ]
        ]));
    }
}
