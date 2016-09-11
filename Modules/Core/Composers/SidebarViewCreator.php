<?php

namespace Modules\Core\Composers;

use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Modules\Core\Sidebar\AdminSidebar;

class SidebarViewCreator
{
    /**
     * @var AdminSidebar
     */
    protected $sidebar;

    /**
     * @var SidebarRenderer
     */
    protected $renderer;

    /**
     * @param AdminSidebar    $sidebar
     * @param SidebarRenderer $renderer
     */
    public function __construct(AdminSidebar $sidebar, SidebarRenderer $renderer)
    {
        $this->sidebar = $sidebar;
        $this->renderer = $renderer;
    }

    public function create($view)
    {
        $view->prefix = config('asgard.core.core.admin-prefix');
        $view->sidebar = $this->renderer->render($this->sidebar);
    }
}
