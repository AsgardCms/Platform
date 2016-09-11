<?php

namespace Modules\Menu\Tests;

class EloquentMenuItemRepositoryTest extends BaseMenuTest
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * New menu item should be created
     * @test
     */
    public function it_should_create_menu_item_as_root()
    {
        $menu = $this->createMenu('second', 'Second Menu');

        $data = [
            'menu_id' => $menu->id,
            'position' => 0,
            'target' => '_self',
            'module_name' => 'blog',
            'en' => [
                'status' => 1,
                'title' => 'First Menu Item',
                'uri' => 'item1',
            ],
            'fr' => [
                'status' => 1,
                'title' => 'Premier item de menu',
                'uri' => 'item1',
            ],
        ];

        $menuItem = $this->menuItem->create($data);

        $this->assertEquals(null, $menuItem->parent_id);
    }

    /** @test */
    public function it_destroys_menu_item()
    {
        // Prepare
        $menu1 = $this->createMenu('main', 'Main menu');
        $item1 = $this->createMenuItemForMenu($menu1->id, 0);
        $item2 = $this->createMenuItemForMenu($menu1->id, 1);
        $item3 = $this->createMenuItemForMenu($menu1->id, 3);

        $menu2 = $this->createMenu('footer', 'Footer menu');
        $secondaryItem1 = $this->createMenuItemForMenu($menu2->id, 0);
        $secondaryItem2 = $this->createMenuItemForMenu($menu2->id, 1);
        $secondaryItem3 = $this->createMenuItemForMenu($menu2->id, 3);

        $this->assertEquals(6, $this->menuItem->all()->count());
        $this->menuItem->destroy($item2);
        $this->assertEquals(5, $this->menuItem->all()->count());
    }
}
