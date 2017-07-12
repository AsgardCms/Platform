<?php

namespace Modules\Menu\Events;

use Modules\Menu\Entities\Menu;

class MenuWasUpdated
{
    /**
     * @var Menu
     */
    public $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }
}
