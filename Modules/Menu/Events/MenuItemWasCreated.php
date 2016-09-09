<?php

namespace Modules\Menu\Events;

class MenuItemWasCreated
{
    public $menuItem;

    public function __construct($menuItem)
    {
        $this->menuItem = $menuItem;
    }
}
