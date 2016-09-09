<?php

namespace Modules\Workshop\Composers;

use Illuminate\Contracts\View\View;
use Modules\Workshop\Manager\ModuleManager;

class MigrateViewComposer
{
    /**
     * @var ModuleManager
     */
    private $module;

    public function __construct(ModuleManager $module)
    {
        $this->module = $module;
    }

    public function compose(View $view)
    {
        $view->modules = $this->module->enabled();
    }
}
