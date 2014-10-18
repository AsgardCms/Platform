<?php namespace Modules\Dashboard\Composers;

use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('dashboard', [
            'weight' => 0,
            'request' => "*/$view->prefix",
            'route' => 'dashboard.index',
            'icon-class' => 'fa fa-dashboard',
            'title' => 'Dashboard',
        ]);
    }
}
