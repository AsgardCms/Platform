<?php namespace Modules\Workshop\Composers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class SidebarViewComposer
{
    public function compose($view)
    {
        $view->items->put('workbench', Collection::make([
            [
                'weight' => '1',
                'request' => Request::is("{$view->prefix}/workshop*"),
                'route' => '#',
                'icon-class' => 'fa fa-cogs',
                'title' => 'Workshop',
            ],
            [
                'request' => "{$view->prefix}/workshop/modules*",
                'route' => 'dashboard.modules.index',
                'icon-class' => 'fa fa-cog',
                'title' => 'Modules',
            ]
        ]));
    }
}