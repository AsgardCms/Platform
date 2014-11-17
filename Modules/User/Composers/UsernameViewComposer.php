<?php namespace Modules\User\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Composers\BaseSidebarViewComposer;

class UsernameViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->with('user', $this->auth->check());
    }
}
