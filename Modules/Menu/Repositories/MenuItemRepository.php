<?php

namespace Modules\Menu\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface MenuItemRepository extends BaseRepository
{
    /**
     * Get online root elements
     *
     * @param  int    $menuId
     * @return object
     */
    public function rootsForMenu($menuId);

    /**
     * Get all root elements
     *
     * @param  int    $menuId
     * @return object
     */
    public function allRootsForMenu($menuId);

    /**
     * Get the menu items ready for routes
     * @return mixed
     */
    public function getForRoutes();

    /**
     * Get the root menu item for the given menu id
     * @param  int    $menuId
     * @return object
     */
    public function getRootForMenu($menuId);

    /**
     * Return a complete tree for the given menu id
     *
     * @param  int    $menuId
     * @return object
     */
    public function getTreeForMenu($menuId);

    /**
     * @param  string $uri
     * @param  string $locale
     * @return object
     */
    public function findByUriInLanguage($uri, $locale);
}
