<?php namespace Modules\Setting\Composers;

use Illuminate\View\View;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('setting', [
            'weight' => 5,
            'request' => "*/$view->prefix/settings*",
            'route' => 'dashboard.setting.index',
            'icon-class' => 'fa fa-cog',
            'title' => 'Settings',
        ]);
    }
}
