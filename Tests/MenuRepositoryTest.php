<?php

namespace Modules\Menu\Tests;

class MenuRepositoryTest extends BaseMenuTest
{
    /** @test */
    public function it_creates_menu()
    {
        $menu = $this->createMenu('main', 'Main Menu');

        $this->assertEquals(1, $this->menu->find($menu->id)->count());
        $this->assertEquals($menu->name, $this->menu->find($menu->id)->name);
    }

    public function it_should_create_root_item_when_creating_new_menu()
    {
        $menu = $this->createMenu('main', 'Main Menu');

        $items = $this->menuItem->allRootsForMenu($menu->id);
    }
}
