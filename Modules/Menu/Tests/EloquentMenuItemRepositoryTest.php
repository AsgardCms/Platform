<?php

namespace Modules\Menu\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Menu\Events\MenuItemIsCreating;
use Modules\Menu\Events\MenuItemIsUpdating;
use Modules\Menu\Events\MenuItemWasCreated;
use Modules\Menu\Events\MenuItemWasUpdated;

class EloquentMenuItemRepositoryTest extends BaseMenuTest
{
    public function setUp(): void
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

        $this->assertEquals(8, $this->menuItem->all()->count());
        $this->menuItem->destroy($item2);
        $this->assertEquals(7, $this->menuItem->all()->count());
    }

    /** @test */
    public function it_triggers_event_when_menu_item_was_created()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');
        $item1 = $this->createMenuItemForMenu($menu->id, 0);

        Event::assertDispatched(MenuItemWasCreated::class, function ($e) use ($item1) {
            return $e->menuItem->id === $item1->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_menu_item_is_creating()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');
        $item1 = $this->createMenuItemForMenu($menu->id, 0);

        Event::assertDispatched(MenuItemIsCreating::class, function ($e) use ($item1) {
            return $e->getAttribute('target') === $item1->target;
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(MenuItemIsCreating::class, function (MenuItemIsCreating $event) {
            $event->setAttributes([
                'target' => '_blank',
                'en' => [
                    'title' => 'My Title',
                ],
            ]);
        });

        $menu = $this->createMenu('main', 'Main Menu');
        $item = $this->createMenuItemForMenu($menu->id, 0);

        $this->assertEquals('_blank', $item->target);
        $this->assertEquals('My Title', $item->translate('en')->title);
    }

    /** @test */
    public function it_triggers_event_when_menu_item_is_updated()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');
        $item1 = $this->createMenuItemForMenu($menu->id, 0);

        $this->menuItem->update($item1, []);

        Event::assertDispatched(MenuItemWasUpdated::class, function ($e) use ($item1) {
            return $e->menuItem->id === $item1->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_menu_item_is_updating()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');
        $item1 = $this->createMenuItemForMenu($menu->id, 0);

        $this->menuItem->update($item1, []);

        Event::assertDispatched(MenuItemIsUpdating::class, function ($e) use ($item1) {
            return $e->getMenuItem()->id === $item1->id;
        });
    }

    /** @test */
    public function it_can_change_data_before_updating_menu_item()
    {
        Event::listen(MenuItemIsUpdating::class, function (MenuItemIsUpdating $event) {
            $event->setAttributes([
                'target' => '_blank',
                'en' => [
                    'title' => 'My Title',
                ],
            ]);
        });

        $menu = $this->createMenu('main', 'Main Menu');
        $item1 = $this->createMenuItemForMenu($menu->id, 0);

        $this->menuItem->update($item1, [
            'en' => ['title' => 'This one!'],
        ]);

        $this->assertEquals('My Title', $item1->translate('en')->title);
    }
}
