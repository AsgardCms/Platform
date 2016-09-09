<?php

namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Http\Requests\CreateMenuItemRequest;
use Modules\Menu\Http\Requests\UpdateMenuItemRequest;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Menu\Services\MenuItemUriGenerator;
use Modules\Page\Repositories\PageRepository;

class MenuItemController extends AdminBaseController
{
    /**
     * @var MenuItemRepository
     */
    private $menuItem;
    /**
     * @var PageRepository
     */
    private $page;
    /**
     * @var MenuItemUriGenerator
     */
    private $menuItemUriGenerator;

    public function __construct(MenuItemRepository $menuItem, PageRepository $page, MenuItemUriGenerator $menuItemUriGenerator)
    {
        parent::__construct();
        $this->menuItem = $menuItem;
        $this->page = $page;
        $this->menuItemUriGenerator = $menuItemUriGenerator;
    }

    public function create(Menu $menu)
    {
        $pages = $this->page->all();

        $menuSelect = $this->getMenuSelect($menu);

        return view('menu::admin.menuitems.create', compact('menu', 'pages', 'menuSelect'));
    }

    public function store(Menu $menu, CreateMenuItemRequest $request)
    {
        $this->menuItem->create($this->addMenuId($menu, $request));

        return redirect()->route('admin.menu.menu.edit', [$menu->id])
            ->withSuccess(trans('menu::messages.menuitem created'));
    }

    public function edit(Menu $menu, Menuitem $menuItem)
    {
        $pages = $this->page->all();

        $menuSelect = $this->getMenuSelect($menu);

        return view('menu::admin.menuitems.edit', compact('menu', 'menuItem', 'pages', 'menuSelect'));
    }

    public function update(Menu $menu, Menuitem $menuItem, UpdateMenuItemRequest $request)
    {
        $this->menuItem->update($menuItem, $this->addMenuId($menu, $request));

        return redirect()->route('admin.menu.menu.edit', [$menu->id])
            ->withSuccess(trans('menu::messages.menuitem updated'));
    }

    public function destroy(Menu $menu, Menuitem $menuItem)
    {
        $this->menuItem->destroy($menuItem);

        return redirect()->route('admin.menu.menu.edit', [$menu->id])
            ->withSuccess(trans('menu::messages.menuitem deleted'));
    }

    /**
     * @param Menu, $menuItemId
     * @return array
     */
    private function getMenuSelect($menu)
    {
        return $menu->menuitems()->where('is_root', '!=', true)->get()->nest()->listsFlattened('title');
    }

    /**
     * @param  Menu $menu
     * @param  \Illuminate\Foundation\Http\FormRequest $request
     * @return array
     */
    private function addMenuId(Menu $menu, FormRequest $request)
    {
        $data = $request->all();

        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $lang) {
            if ($data['link_type'] === 'page' && ! empty($data['page_id'])) {
                $data[$lang]['uri'] = $this->menuItemUriGenerator->generateUri($data['page_id'], $data['parent_id'], $lang);
            }
        }

        if (empty($data['parent_id'])) {
            $data['parent_id'] = $this->menuItem->getRootForMenu($menu->id)->id;
        }

        return array_merge($data, ['menu_id' => $menu->id]);
    }
}
