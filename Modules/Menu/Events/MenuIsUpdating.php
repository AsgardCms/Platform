<?php

namespace Modules\Menu\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Menu\Entities\Menu;

class MenuIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var Menu
     */
    private $menu;

    public function __construct(Menu $menu, $attributes)
    {
        $this->menu = $menu;
        parent::__construct($attributes);
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
