<?php namespace Modules\Core\Composers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class SidebarViewCreator
{
    public function create($view)
    {
        $view->prefix = Config::get('core::core.admin-prefix');
        $view->items = new Collection;
    }
}
