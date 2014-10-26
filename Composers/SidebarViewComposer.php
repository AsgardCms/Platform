<?php namespace Modules\Workshop\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('workbench', Collection::make([
            [
                'weight' => '1',
                'request' => Request::is("*/{$view->prefix}/modules*") or Request::is("*/{$view->prefix}/workbench*"),
                'route' => '#',
                'icon-class' => 'fa fa-cogs',
                'title' => 'Workshop',
                'permission' => $this->auth->hasAccess('modules.index') or $this->auth->hasAccess('workbench.index')
            ],
            [
                'request' => "*/{$view->prefix}/modules*",
                'route' => 'dashboard.modules.index',
                'icon-class' => 'fa fa-cog',
                'title' => 'Modules',
                'permission' => $this->auth->hasAccess('modules.index')
            ],
            [
                'request' => "*/{$view->prefix}/workbench*",
                'route' => 'dashboard.workbench.index',
                'icon-class' => 'fa fa-terminal',
                'title' => 'Workbench',
                'permission' => $this->auth->hasAccess('workbench.index')
            ]
        ]));
    }
}
