<?php namespace Modules\Dashboard\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('dashboard', [
            'weight' => 0,
            'request' => "*/$view->prefix",
            'route' => 'dashboard.index',
            'icon-class' => 'fa fa-dashboard',
            'title' => 'Dashboard',
            'permission' => $this->auth->hasAccess('dashboard.index')
        ]);
    }
}
