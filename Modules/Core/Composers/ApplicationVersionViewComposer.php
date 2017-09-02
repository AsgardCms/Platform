<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Foundation\AsgardCms;

class ApplicationVersionViewComposer
{
    public function compose(View $view)
    {
        if (app('asgard.onBackend') === false) {
            return;
        }
        $view->with('version', AsgardCms::VERSION);
    }
}
