<?php

namespace Modules\Menu\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Menu\Events\MenuIsCreating;
use Modules\Menu\Events\MenuIsUpdating;
use Modules\Menu\Events\MenuWasCreated;
use Modules\Menu\Events\MenuWasUpdated;

class EloquentMenuRepositoryTest extends BaseMenuTest
{
    /** @test */
    public function it_creates_menu()
    {
        $menu = $this->createMenu('main', 'Main Menu');

        $this->assertEquals(1, $this->menu->find($menu->id)->count());
        $this->assertEquals($menu->name, $this->menu->find($menu->id)->name);
    }

    /** @test */
    public function it_triggers_event_when_menu_was_created()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');

        Event::assertDispatched(MenuWasCreated::class, function ($e) use ($menu) {
            return $e->menu->id === $menu->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_menu_is_creating()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');

        Event::assertDispatched(MenuIsCreating::class, function ($e) use ($menu) {
            return $e->getAttribute('name') === $menu->name;
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(MenuIsCreating::class, function (MenuIsCreating $event) {
            $event->setAttributes(['name' => 'MAIN']);
        });

        $menu = $this->createMenu('main', 'Main Menu');

        $this->assertEquals('MAIN', $menu->name);
    }

    /** @test */
    public function it_triggers_event_when_menu_item_was_updated()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');
        $this->menu->update($menu, []);

        Event::assertDispatched(MenuWasUpdated::class, function ($e) use ($menu) {
            return $e->menu->id === $menu->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_menu_is_updating()
    {
        Event::fake();

        $menu = $this->createMenu('main', 'Main Menu');
        $this->menu->update($menu, []);

        Event::assertDispatched(MenuIsUpdating::class, function ($e) use ($menu) {
            return $e->getMenu()->id === $menu->id;
        });
    }

    /** @test */
    public function it_can_change_attributes_before_update()
    {
        Event::listen(MenuIsUpdating::class, function (MenuIsUpdating $event) {
            $event->setAttributes(['name' => 'MAIN']);
        });

        $menu = $this->createMenu('main', 'Main Menu');
        $this->menu->update($menu, ['name' => 'better-one']);

        $this->assertEquals('MAIN', $menu->name);
    }

    /** @test */
    public function it_should_create_root_item_when_creating_new_menu()
    {
        $menu = $this->createMenu('main', 'Main Menu');

        $items = $this->menuItem->allRootsForMenu($menu->id);
        $this->assertCount(1, $items);
    }
}
