<?php

namespace Modules\Core\Tests\Theme;

use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Core\Tests\BaseTestCase;

class ThemeManagerTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Theme\ThemeManager
     */
    protected $repository;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->repository = new ThemeManager($this->app, $this->getPath());
    }

    /** @test */
    public function it_should_return_all_themes()
    {
        $this->assertTrue(is_array($this->repository->all()));
        $this->assertEquals($this->repository->count(), 2);
    }

    /** @test */
    public function it_should_return_a_theme()
    {
        $theme = $this->repository->find('demo');

        $this->assertInstanceOf('Modules\Core\Foundation\Theme\Theme', $theme);
        $this->assertEquals('demo', $theme->getLowerName());
    }

    /** @test */
    public function it_should_return_null_if_not_theme_found()
    {
        $theme = $this->repository->find('fakeTheme');

        $this->assertNull($theme);
    }

    /** @test */
    public function it_should_return_empty_array_if_no_themes()
    {
        $repository = new ThemeManager($this->app, $this->getEmptyThemesPath());

        $this->assertEquals([], $repository->all());
    }

    /** @test */
    public function it_should_return_empty_array_if_no_folder()
    {
        $repository = new ThemeManager($this->app, $this->getFakePath());

        $this->assertEquals([], $repository->all());
    }

    private function getPath()
    {
        return __DIR__ . '/Fixture/Themes';
    }

    private function getEmptyThemesPath()
    {
        return __DIR__ . '/Fixture/EmptyThemes';
    }

    private function getFakePath()
    {
        return __DIR__ . '/Fixture/fakeFolder';
    }
}
