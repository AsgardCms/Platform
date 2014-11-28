<?php  namespace Modules\Core\Tests\Theme;

use Modules\Core\Foundation\Theme\AssetPublisher;
use Modules\Core\Foundation\Theme\Theme;
use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Core\Tests\BaseTestCase;

class AssetPublisherTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Theme\AssetPublisher
     */
    protected $publisher;
    /**
     * @var \Modules\Core\Foundation\Theme\Theme
     */
    protected $theme;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->theme = new Theme('testAssets', $this->getThemePath());
        $this->publisher = with(new AssetPublisher($this->theme))
            ->setFinder($this->finder)
            ->setRepository(new ThemeManager($this->app, $this->getPath()));
    }

    /** @test */
    public function it_gets_the_source_path()
    {
        $this->assertEquals($this->getThemePath() . '/assets', $this->publisher->getSourcePath());
    }

    /** @test */
    public function it_gets_the_destination_path()
    {
        $expectedPath = $this->getAssetsThemePath();

        $this->assertEquals($expectedPath, $this->publisher->getDestinationPath());
    }

    /** @test */
    public function it_publishes_the_assets()
    {
        $this->publisher->publish();

        $this->assertTrue($this->finder->isDirectory($this->getAssetsThemePath()));
        $this->assertTrue($this->finder->isDirectory($this->getAssetsThemePath() . '/css'));
        $this->assertTrue($this->finder->isDirectory($this->getAssetsThemePath() . '/js'));
        $this->assertTrue($this->finder->isFile($this->getAssetsThemePath() . '/css/main.css'));
        $this->assertTrue($this->finder->isFile($this->getAssetsThemePath() . '/js/main.js'));
    }

    private function getThemePath()
    {
        return __DIR__ . '/Fixture/Themes/testAssets';
    }

    private function getAssetsThemePath()
    {
        return public_path($this->app['config']->get('themify::themes_assets_path') . '/' . 'testassets');
    }

    private function getPath()
    {
        return __DIR__ . '/Fixture/Themes';
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->getAssetsThemePath());
    }
}
