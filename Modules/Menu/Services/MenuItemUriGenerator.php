<?php

namespace Modules\Menu\Services;

use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Page\Repositories\PageRepository;

final class MenuItemUriGenerator
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;
    /**
     * @var PageRepository
     */
    private $page;

    public function __construct(MenuItemRepository $menuItem, PageRepository $page)
    {
        $this->menuItem = $menuItem;
        $this->page = $page;
    }

    /**
     * Generate a URI based of the given page and and the parent id recursively
     * @param string $pageId
     * @param string $parentId
     * @param string $lang
     * @return string
     */
    public function generateUri($pageId, $parentId, $lang)
    {
        $linkPathArray = [];

        $linkPathArray[] = $this->getPageSlug($pageId, $lang);

        if ($parentId !== '') {
            $hasParentItem = !(is_null($parentId)) ? true : false;
            while ($hasParentItem) {
                $parentItemId = isset($parentItem) ? $parentItem->parent_id : $parentId;
                $parentItem = $this->menuItem->find($parentItemId);

                if ((int) $parentItem->is_root === 0) {
                    if ($parentItem->page_id != '') {
                        $linkPathArray[] = $this->getPageSlug($parentItem->page_id, $lang);
                    } else {
                        $linkPathArray[] = $this->getParentUri($parentItem, $linkPathArray);
                    }
                    $hasParentItem = !is_null($parentItem->parent_id) ? true : false;
                } else {
                    $hasParentItem = false;
                }
            }
        }
        $parentLinkPath = implode('/', array_reverse($linkPathArray));

        return $parentLinkPath;
    }

    /**
     * Get page slug
     * @param $id
     * @param $lang
     * @return string
     */
    private function getPageSlug($id, $lang)
    {
        $page = $this->page->find($id);
        $translation = $page->translate($lang);

        if ($translation === null) {
            return $page->translate(config('app.fallback_locale'))->slug;
        }

        return $translation->slug;
    }

    /**
     * Get parent uri
     *
     * @params $pageId, $lang
     * @return string
     */
    private function getParentUri($item, $linkPathArray)
    {
        if ($item->uri === null) {
            return implode('/', $linkPathArray);
        }

        return $item->uri;
    }
}
