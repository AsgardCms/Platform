<?php namespace Modules\Setting\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('setting', [
            'weight' => 5,
            'request' => "*/$view->prefix/settings*",
            'route' => 'dashboard.setting.index',
            'icon-class' => 'fa fa-cog',
            'title' => 'Settings',
            'permission' => $this->auth->hasAccess('settings.index')
        ]);
    }
}
