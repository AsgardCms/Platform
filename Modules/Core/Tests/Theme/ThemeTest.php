<?php

namespace Modules\Core\Tests\Theme;

use Modules\Core\Foundation\Theme\Theme;
use Modules\Core\Tests\BaseTestCase;

class ThemeTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Theme\Theme
     */
    protected $theme;

    public function setUp()
    {
        parent::setUp();

        $this->theme = new Theme('demo', $this->getPath());
    }

    /** @test */
    public function it_should_return_name()
    {
        $this->assertEquals('Demo', $this->theme->getName());
    }

    /** @test */
    public function it_should_return_name_in_lowercase()
    {
        $this->assertEquals('demo', $this->theme->getLowerName());
    }

    /** @test */
    public function it_should_return_correct_path()
    {
        $this->assertEquals($this->getPath(), $this->theme->getPath());
    }

    private function getPath()
    {
        return __DIR__ . '/Fixture/Themes/demo';
    }
}
