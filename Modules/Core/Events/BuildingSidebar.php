<?php

namespace Modules\Core\Events;

use Maatwebsite\Sidebar\Menu;

/**
 * Hook BuildingSidebar
 * Triggered when building the backend sidebar
 * Use this hook to add your sidebar items
 * @package Modules\Core\Events
 */
class BuildingSidebar
{
    /**
     * @var Menu
     */
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Add a menu group to the menu
     * @param Menu $menu
     */
    public function add(Menu $menu)
    {
        $this->menu->add($menu);
    }

    /**
     * Get the current Laravel-Sidebar menu
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
