<?php namespace Modules\Media\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('medias', [
            'weight' => 6,
            'request' => "*/$view->prefix/media*",
            'route' => 'dashboard.media.index',
            'icon-class' => 'fa fa-camera',
            'title' => 'Medias',
            'permission' => $this->auth->hasAccess('media.index')
        ]);
    }
}
