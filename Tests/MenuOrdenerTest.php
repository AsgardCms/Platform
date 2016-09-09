<?php

namespace Modules\Menu\Tests;

class MenuOrdenerTest extends BaseMenuTest
{
    /**
     * @var \Modules\Menu\Services\MenuOrdener
     */
    protected $menuOrdener;

    public function setUp()
    {
        parent::setUp();
        $this->createMenu('main', 'Main Menu');
        $this->menuOrdener = app('Modules\Menu\Services\MenuOrdener');
    }

    /** @test */
    public function it_makes_item_child_of()
    {
        // Prepare
        $menu = $this->createMenu('main', 'Main Menu');
        $menuItem1 = $this->createMenuItemForMenu($menu->id, 1);
        $menuItem2 = $this->createMenuItemForMenu($menu->id, 2);
        $request = [
            1 => [
                'id' => $menuItem1->id,
                'children' => [
                    0 => [
                        'id' => $menuItem2->id,
                    ],
                ],
            ],
        ];
        $request = json_encode($request);

        // Run
        $this->menuOrdener->handle($request);

        // Assert
        $child = $this->menuItem->find($menuItem2->id);
        $this->assertEquals($menuItem1->id, $child->parent_id);
    }

    /** @test */
    public function it_makes_items_child_of_recursively()
    {
        // Prepare
        $menu = $this->createMenu('main', 'Main Menu');
        $menuItem1 = $this->createMenuItemForMenu($menu->id, 0);
        $menuItem2 = $this->createMenuItemForMenu($menu->id, 0, $menuItem1->id);
        $menuItem3 = $this->createMenuItemForMenu($menu->id, 1, $menuItem1->id);
        $request = [
            1 => [
                'id' => $menuItem1->id,
                'children' => [
                    0 => [
                        'id' => $menuItem2->id,
                        'children' => [
                            0 => [
                                'id' => $menuItem3->id,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $request = json_encode($request);

        // Run
        $this->menuOrdener->handle($request);

        // Assert
        $child = $this->menuItem->find($menuItem2->id);
        $this->assertEquals($menuItem1->id, $child->parent_id);
        $child2 = $this->menuItem->find($menuItem3->id);
        $this->assertEquals($menuItem2->id, $child2->parent_id);
    }

    /** @test */
    public function it_reorders_items()
    {
        // Prepare
        $menu = $this->createMenu('main', 'Main Menu');
        $menuItem1 = $this->createMenuItemForMenu($menu->id, 1);
        $menuItem2 = $this->createMenuItemForMenu($menu->id, 2);

        $request = [
            1 => [
                'id' => $menuItem2->id,
            ],
            2 => [
                'id' => $menuItem1->id,
            ],
        ];
        $request = json_encode($request);

        // Run
        $this->menuOrdener->handle($request);

        // Assert
        $item1 = $this->menuItem->find($menuItem1->id);
        $this->assertEquals(2, $item1->position);
        $item2 = $this->menuItem->find($menuItem2->id);
        $this->assertEquals(1, $item2->position);
    }

    /** @test */
    public function it_moves_items_to_root()
    {
        // Prepare
        $menu = $this->createMenu('main', 'Main Menu');
        $menuItem1 = $this->createMenuItemForMenu($menu->id, 0);
        $menuItem2 = $this->createMenuItemForMenu($menu->id, 1, $menuItem1->id);

        $request = [
            0 => [
                'id' => $menuItem1->id,
            ],
            1 => [
                'id' => $menuItem2->id,
            ],
        ];
        $request = json_encode($request);

        // Run
        $this->menuOrdener->handle($request);

        // Assert
        $item1 = $this->menuItem->find($menuItem1->id);
        $this->assertEquals(null, $item1->parent_id);
        $item2 = $this->menuItem->find($menuItem2->id);
        $this->assertEquals(null, $item2->parent_id);
    }
}
