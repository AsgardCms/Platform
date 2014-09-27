<?php namespace Modules\Workshop\Composers;

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

    public function compose($view)
    {
        $view->modules = $this->module->enabled();
    }
}