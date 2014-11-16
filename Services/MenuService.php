<?php namespace Modules\Menu\Services;

use Modules\Menu\Repositories\MenuItemRepository;

class MenuService
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(MenuItemRepository $menuItem)
    {
        $this->menuItem = $menuItem;
    }

    public function handle($item, $position)
    {
        // Find menu item : $menuItem['id'] ->setRoot
        $menuItem = $this->menuItem->find($item['id']);
        $menuItem->position = $position;
        $menuItem->save();
        $menuItem->makeRoot();

        // If hasChildren ? set parent ˆ (recursive)
        if (isset($item['children'])) {
            foreach ($item['children'] as $childPosition => $childItem) {
                $childMenuItem = $this->menuItem->find($childItem['id']);
                $childMenuItem->position = $childPosition;
                $childMenuItem->save();
                $childMenuItem->makeChildOf($menuItem);
            }
        }
        // If no more children finish the recursive call
    }
}
