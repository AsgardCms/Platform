<?php namespace Modules\Dashboard\Composers;

class SidebarViewComposer
{
    public function compose($view)
    {
        $view->items->put('dashboard', [
            'weight' => 0,
            'request' => $view->prefix,
            'route' => 'dashboard.index',
            'icon-class' => 'fa fa-dashboard',
            'title' => 'Dashboard',
        ]);
    }
}