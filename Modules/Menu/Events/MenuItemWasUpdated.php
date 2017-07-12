<?php

namespace Modules\Menu\Events;

use Modules\Menu\Entities\Menuitem;

class MenuItemWasUpdated
{
    /**
     * @var Menuitem
     */
    public $menuItem;

    public function __construct(Menuitem $menuItem)
    {
        $this->menuItem = $menuItem;
    }
}
