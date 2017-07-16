<?php

namespace Modules\Menu\Tests;

use Illuminate\Support\Facades\Event;
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

    public function setUp()
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
        Event::fake();
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
        $this->menuItem->create($data);

        self::assertEquals('awesome-page/about', $this->menuItemUriGenerator->generateUri(2, '1', 'en'));
    }

    /** @test */
    public function it_generates_uri_with_multiple_parents()
    {
        Event::fake();
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
        $this->menuItem->create($data);
        $data = [
            'menu_id' => $menu->id,
            'position' => 0,
            'target' => '_self',
            'page_id' => 2,
            'parent_id' => 1,
            'en' => [
                'status' => 1,
                'title' => 'Second Menu Item',
                'uri' => 'awesome-page/mid-page',
            ],
        ];
        $this->menuItem->create($data);

        self::assertEquals('awesome-page/mid-page/about', $this->menuItemUriGenerator->generateUri(3, '2', 'en'));
    }

    /** @test */
    public function it_generates_a_uri_if_parent_isnt_a_page()
    {
        Event::fake();
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
        $this->menuItem->create($data);

        self::assertEquals('awesome-page/about', $this->menuItemUriGenerator->generateUri(1, '1', 'en'));
    }
}
