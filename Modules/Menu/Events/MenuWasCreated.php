<?php

namespace Modules\Menu\Events;

class MenuWasCreated
{
    public $menu;

    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}
