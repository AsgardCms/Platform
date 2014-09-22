<?php namespace Modules\Core\Composers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class SidebarViewCreator
{
    public function create($view)
    {
        $view->prefix = Config::get('core::core.admin-prefix');
        $view->items = new Collection;
        $view->items->put('dashboard', [
                'weight' => 0,
                'request' => $view->prefix,
                'route' => 'dashboard.index',
                'icon-class' => 'fa fa-dashboard',
                'title' => 'Dashboard',
            ]);
    }
}