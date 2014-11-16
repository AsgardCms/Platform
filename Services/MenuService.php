<?php namespace Modules\Menu\Services;

use Modules\Menu\Repositories\MenuItemRepository;

class MenuService
{
    /**
     * Current Menu Item being looped over
     * @var
     */
    protected $menuItem;
    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    public function __construct(MenuItemRepository $menuItem)
    {
        $this->menuItemRepository = $menuItem;
    }

    public function handle($item, $position)
    {
        // Find menu item : $menuItem['id'] ->setRoot
        $this->menuItem = $this->menuItemRepository->find($item['id']);
        $this->savePosition($this->menuItem, $position);
        $this->menuItem->makeRoot();

        // If hasChildren ? set parent ˆ (recursive)
        if ($this->hasChildren($item)) {
            $this->setChildrenRecursively($item, $this->menuItem);
        }
    }

    /**
     * @param $item
     * @return bool
     */
    private function hasChildren($item)
    {
        return isset($item['children']);
    }

    private function setChildrenRecursively($item, $parent)
    {
        foreach ($item['children'] as $childPosition => $childItem) {
            $childMenuItem = $this->menuItemRepository->find($childItem['id']);
            $this->savePosition($childMenuItem, $childPosition);
            $childMenuItem->makeChildOf($parent);
            if ($this->hasChildren($childItem)) $this->setChildrenRecursively($childItem, $childMenuItem);
        }
    }

    /**
     * @param $item
     * @param $position
     */
    private function savePosition($item, $position)
    {
        $item->position = $position;
        $item->save();
    }
}
