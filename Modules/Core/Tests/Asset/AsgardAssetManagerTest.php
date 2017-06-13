<?php

namespace Modules\Core\Tests\Asset;

use Modules\Core\Foundation\Asset\Manager\AsgardAssetManager;
use Modules\Core\Tests\BaseTestCase;

class AsgardAssetManagerTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Asset\Manager\AsgardAssetManager
     */
    private $assetManager;

    public function setUp()
    {
        parent::__construct();
        $this->assetManager = new AsgardAssetManager();
    }

    /** @test */
    public function it_should_return_empty_collection_if_no_assets()
    {
        $cssResult = $this->assetManager->allCss();
        $jsResult = $this->assetManager->allJs();

        $this->assertInstanceOf('Illuminate\Support\Collection', $cssResult);
        $this->assertEquals(0, $cssResult->count());
        $this->assertInstanceOf('Illuminate\Support\Collection', $jsResult);
        $this->assertEquals(0, $jsResult->count());
    }

    /** @test */
    public function it_should_add_one_javascript_asset()
    {
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $jsResult = $this->assetManager->allJs();

        $this->assertEquals(1, $jsResult->count());
    }

    /** @test */
    public function it_should_add_one_css_asset()
    {
        $this->assetManager->addAsset('main', '/path/to/main.css');

        $cssResult = $this->assetManager->allCss();

        $this->assertEquals(1, $cssResult->count());
    }

    /** @test */
    public function it_should_add_multiple_assets()
    {
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('footer', '/path/to/footer.css');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');

        $cssResults = $this->assetManager->allCss();
        $jsResults = $this->assetManager->allJs();

        $this->assertEquals(2, $cssResults->count());
        $this->assertEquals(2, $jsResults->count());
    }

    /** @test */
    public function it_should_return_the_dependency_asked_for()
    {
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('footer', '/path/to/footer.css');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');

        $jquery = $this->assetManager->getJs('jquery');
        $footer = $this->assetManager->getCss('footer');

        $this->assertEquals('/path/to/jquery.js', $jquery);
        $this->assertEquals('/path/to/footer.css', $footer);
    }

    /** @test */
    public function it_should_throw_an_exception_if_js_asset_not_found()
    {
        $this->expectException('Modules\Core\Foundation\Asset\AssetNotFoundException');

        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $this->assetManager->getJs('app');
    }

    /** @test */
    public function it_should_throw_an_exception_if_css_asset_not_found()
    {
        $this->expectException('Modules\Core\Foundation\Asset\AssetNotFoundException');

        $this->assetManager->addAsset('main', '/path/to/main.css');

        $this->assetManager->getCss('iCheck');
    }

    /** @test */
    public function it_should_add_an_array_of_assets()
    {
        $this->assetManager->addAssets([
            'jquery' => '/path/to/jquery.js',
            'plugin' => '/path/to/plugin.js',
            'main' => '/path/to/main.css',
            'icheck' => '/path/to/icheck.css',
        ]);

        $jsAssets = $this->assetManager->allJs();
        $cssAssets = $this->assetManager->allCss();

        $expectedJs = [
            'jquery' => '/path/to/jquery.js',
            'plugin' => '/path/to/plugin.js',
        ];
        $expectedCss = [
            'main' => '/path/to/main.css',
            'icheck' => '/path/to/icheck.css',
        ];

        $this->assertEquals($expectedJs, $jsAssets->toArray());
        $this->assertEquals($expectedCss, $cssAssets->toArray());
    }
}
