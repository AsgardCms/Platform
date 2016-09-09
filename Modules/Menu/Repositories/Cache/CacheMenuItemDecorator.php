<?php

namespace Modules\Menu\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Menu\Repositories\MenuItemRepository;

class CacheMenuItemDecorator extends BaseCacheDecorator implements MenuItemRepository
{
    /**
     * @var MenuItemRepository
     */
    protected $repository;

    public function __construct(MenuItemRepository $menuItem)
    {
        parent::__construct();
        $this->entityName = 'menusItems';
        $this->repository = $menuItem;
    }

    /**
     * Get all root elements
     *
     * @param  int   $menuId
     * @return mixed
     */
    public function rootsForMenu($menuId)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.rootsForMenu.{$menuId}", $this->cacheTime,
                function () use ($menuId) {
                    return $this->repository->rootsForMenu($menuId);
                }
            );
    }

    /**
     * Get the menu items ready for routes
     *
     * @return mixed
     */
    public function getForRoutes()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.getForRoutes", $this->cacheTime,
                function () {
                    return $this->repository->getForRoutes();
                }
            );
    }

    /**
     * Get the root menu item for the given menu id
     *
     * @param  int    $menuId
     * @return object
     */
    public function getRootForMenu($menuId)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.getRootForMenu.{$menuId}", $this->cacheTime,
                function () use ($menuId) {
                    return $this->repository->getRootForMenu($menuId);
                }
            );
    }

    /**
     * Return a complete tree for the given menu id
     *
     * @param  int    $menuId
     * @return object
     */
    public function getTreeForMenu($menuId)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.getTreeForMenu.{$menuId}", $this->cacheTime,
                function () use ($menuId) {
                    return $this->repository->getTreeForMenu($menuId);
                }
            );
    }

    /**
     * Get all root elements
     *
     * @param  int    $menuId
     * @return object
     */
    public function allRootsForMenu($menuId)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.allRootsForMenu.{$menuId}", $this->cacheTime,
                function () use ($menuId) {
                    return $this->repository->allRootsForMenu($menuId);
                }
            );
    }

    /**
     * @param  string $uri
     * @param  string $locale
     * @return object
     */
    public function findByUriInLanguage($uri, $locale)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByUriInLanguage.{$uri}.{$locale}", $this->cacheTime,
                function () use ($uri, $locale) {
                    return $this->repository->findByUriInLanguage($uri, $locale);
                }
            );
    }
}
