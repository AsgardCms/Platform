<?php

namespace Modules\Menu\Composers;

use Modules\Menu\Entities\Menuitem as MenuitemEntity;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Menu\Repositories\MenuRepository;
use Nwidart\Menus\Builder;
use Nwidart\Menus\Facades\Menu;
use Nwidart\Menus\MenuItem;

class NavigationViewComposer
{
    /**
     * @var MenuRepository
     */
    private $menu;
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(MenuRepository $menu, MenuItemRepository $menuItem)
    {
        $this->menu = $menu;
        $this->menuItem = $menuItem;
    }

    public function compose()
    {
        foreach ($this->menu->all() as $menu) {
            $menuTree = $this->menuItem->getTreeForMenu($menu->id);
            Menu::create($menu->name, function (Builder $menu) use ($menuTree) {
                foreach ($menuTree as $menuItem) {
                    $this->addItemToMenu($menuItem, $menu);
                }
            });
        }
    }

    /**
     * Add a menu item to the menu
     * @param MenuitemEntity $item
     * @param Builder $menu
     */
    public function addItemToMenu(MenuitemEntity $item, Builder $menu)
    {
        if ($this->hasChildren($item)) {
            $this->addChildrenToMenu($item->title, $item->items, $menu);
        } else {
            $target = $item->uri ?: $item->url;
            $menu->url(
                $target,
                $item->title,
                ['target' => $item->target]
            );
        }
    }

    /**
     * Add children to menu under the give name
     *
     * @param string $name
     * @param object $children
     * @param Builder|MenuItem $menu
     */
    private function addChildrenToMenu($name, $children, $menu)
    {
        $menu->dropdown($name, function (MenuItem $subMenu) use ($children) {
            foreach ($children as $child) {
                $this->addSubItemToMenu($child, $subMenu);
            }
        });
    }

    /**
     * Add children to the given menu recursively
     *
     * @param MenuitemEntity   $child
     * @param MenuItem $sub
     */
    private function addSubItemToMenu(MenuitemEntity $child, MenuItem $sub)
    {
        $sub->url($child->uri, $child->title);

        if ($this->hasChildren($child)) {
            $this->addChildrenToMenu($child->title, $child->items, $sub);
        }
    }

    /**
     * Check if the given menu item has children
     *
     * @param  object $item
     * @return bool
     */
    private function hasChildren($item)
    {
        return $item->items->count() > 0;
    }
}
