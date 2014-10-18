<?php namespace Modules\Setting\Composers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('setting', [
            'weight' => 5,
            'request' => "*/$view->prefix/settings",
            'route' => 'dashboard.setting.index',
            'icon-class' => 'fa fa-cog',
            'title' => 'Settings',
        ]);
    }
}
