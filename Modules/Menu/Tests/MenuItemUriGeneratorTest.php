<?php

namespace Modules\Menu\Tests;

use Modules\Menu\Services\MenuItemUriGenerator;
use Modules\Page\Repositories\PageRepository;

class MenuItemUriGeneratorTest extends BaseMenuTest
{
    /**
     * @var PageRepository
     */
    private $page;
    /**
     * @var MenuItemUriGenerator
     */
    private $menuItemUriGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->page = app(PageRepository::class);
        $this->menuItemUriGenerator = app(MenuItemUriGenerator::class);
    }

    /** @test */
    public function it_generates_basic_uri_without_parent()
    {
        $this->page->create([
            'is_home' => 1,
            'template' => 'default',
            'en' => [
                'title' => 'Awesome Page',
                'slug' => 'awesome-page',
                'body' => 'My Page Body',
            ],
        ]);

        self::assertEquals('awesome-page', $this->menuItemUriGenerator->generateUri(1, '', 'en'));
    }

    /** @test */
    public function it_generates_uri_with_the_parents_slug()
    {
        $this->page->create([
            'is_home' => 1,
            'template' => 'default',
            'en' => [
                'title' => 'Awesome Page',
                'slug' => 'awesome-page',
                'body' => 'My Page Body',
            ],
        ]);
        $this->page->create([
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'title' => 'About',
                'slug' => 'about',
                'body' => 'My Page Body',
            ],
        ]);
        $menu = $this->createMenu('main', 'Main');
        $data = [
            'menu_id' => $menu->id,
            'position' => 0,
            'target' => '_self',
            'page_id' => 1,
            'en' => [
                'status' => 1,
                'title' => 'First Menu Item',
                'uri' => 'awesome-page',
            ],
        ];
        $menuitem = $this->menuItem->create($data);

        self::assertEquals('awesome-page/about', $this->menuItemUriGenerator->generateUri(2, $menuitem->id, 'en'));
    }

    /** @test */
    public function it_generates_uri_with_multiple_parents()
    {
        $this->page->create([
            'is_home' => 1,
            'template' => 'default',
            'en' => [
                'title' => 'Awesome Page',
                'slug' => 'awesome-page',
                'body' => 'My Page Body',
            ],
        ]);
        $this->page->create([
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'title' => 'Mid Page',
                'slug' => 'mid-page',
                'body' => 'My Page Body',
            ],
        ]);
        $this->page->create([
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'title' => 'About',
                'slug' => 'about',
                'body' => 'My Page Body',
            ],
        ]);
        $menu = $this->createMenu('main', 'Main');
        $data = [
            'menu_id' => $menu->id,
            'position' => 0,
            'target' => '_self',
            'page_id' => 1,
            'en' => [
                'status' => 1,
                'title' => 'First Menu Item',
                'uri' => 'awesome-page',
            ],
        ];
        $menuitem1 = $this->menuItem->create($data);
        $data = [
            'menu_id' => $menu->id,
            'position' => 0,
            'target' => '_self',
            'page_id' => 2,
            'parent_id' => $menuitem1->id,
            'en' => [
                'status' => 1,
                'title' => 'Second Menu Item',
                'uri' => 'awesome-page/mid-page',
            ],
        ];
        $menuitem2 = $this->menuItem->create($data);

        self::assertEquals('awesome-page/mid-page/about', $this->menuItemUriGenerator->generateUri(3, $menuitem2->id, 'en'));
    }

    /** @test */
    public function it_generates_a_uri_if_parent_isnt_a_page()
    {
        $this->page->create([
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'title' => 'About',
                'slug' => 'about',
                'body' => 'My Page Body',
            ],
        ]);

        $menu = $this->createMenu('main', 'Main');
        $data = [
            'menu_id' => $menu->id,
            'position' => 0,
            'target' => '_self',
            'page_id' => null,
            'en' => [
                'status' => 1,
                'title' => 'First Menu Item',
                'uri' => 'awesome-page',
            ],
        ];
        $menuitem = $this->menuItem->create($data);

        self::assertEquals('awesome-page/about', $this->menuItemUriGenerator->generateUri(1, $menuitem->id, 'en'));
    }
}
