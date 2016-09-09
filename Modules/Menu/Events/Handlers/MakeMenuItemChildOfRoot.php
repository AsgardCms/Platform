<?php

namespace Modules\Menu\Events\Handlers;

use Modules\Menu\Events\MenuItemWasCreated;
use Modules\Menu\Repositories\MenuItemRepository;

class MakeMenuItemChildOfRoot
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(MenuItemRepository $menuItem)
    {
        $this->menuItem = $menuItem;
    }

    public function handle(MenuItemWasCreated $event)
    {
        $root = $this->menuItem->getRootForMenu($event->menuItem->menu_id);

        if (! $this->isRoot($event->menuItem)) {
            $event->menuItem->makeChildOf($root);
        }
    }

    /**
     * Check if the given menu item is not already a root menu item
     * @param  object $menuItem
     * @return bool
     */
    private function isRoot($menuItem)
    {
        return (bool) $menuItem->is_root;
    }
}
