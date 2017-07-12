<?php

namespace Modules\Menu\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Menu\Entities\Menuitem;

class MenuItemIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var Menuitem
     */
    private $menuItem;

    public function __construct(Menuitem $menuItem, $attributes)
    {
        parent::__construct($attributes);
        $this->menuItem = $menuItem;
    }

    /**
     * @return Menuitem
     */
    public function getMenuItem()
    {
        return $this->menuItem;
    }
}
