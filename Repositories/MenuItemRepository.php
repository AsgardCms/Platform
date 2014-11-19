<?php namespace Modules\Menu\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface MenuItemRepository extends BaseRepository
{
    /**
     * Get all root elements
     * @return mixed
     */
    public function rootsForMenu($menuId);

    /**
     * Get the menu items ready for routes
     * @return mixed
     */
    public function getForRoutes();
}
