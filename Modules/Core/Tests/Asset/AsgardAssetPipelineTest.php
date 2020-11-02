<?php

namespace Modules\Core\Tests\Asset;

use Modules\Core\Foundation\Asset\Pipeline\AsgardAssetPipeline;
use Modules\Core\Tests\BaseTestCase;

class AsgardAssetPipelineTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Asset\Pipeline\AsgardAssetPipeline
     */
    private $assetPipeline;
    /**
     * @var \Modules\Core\Foundation\Asset\Manager\AsgardAssetManager
     */
    private $assetManager;

    /**
     *
     */
    public function setUp(): void
    {
        parent::__construct();
        $this->refreshApplication();
        $this->assetPipeline = new AsgardAssetPipeline($this->app['Modules\Core\Foundation\Asset\Manager\AssetManager']);
        $this->assetManager = $this->app['Modules\Core\Foundation\Asset\Manager\AssetManager'];
    }

    /** @test */
    public function it_should_return_empty_collection_if_no_assets()
    {
        $cssResult = $this->assetPipeline->allCss();
        $jsResult = $this->assetPipeline->allJs();

        $this->assertInstanceOf('Illuminate\Support\Collection', $cssResult);
        $this->assertEquals(0, $cssResult->count());
        $this->assertInstanceOf('Illuminate\Support\Collection', $jsResult);
        $this->assertEquals(0, $jsResult->count());
    }

    /** @test */
    public function it_should_require_add_js_asset()
    {
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $this->assetPipeline->requireJs('jquery');

        $jsAssets = $this->assetPipeline->allJs();

        $this->assertEquals('/path/to/jquery.js', $jsAssets->first());
    }

    /** @test */
    public function it_should_require_a_css_asset()
    {
        $this->assetManager->addAsset('main', '/path/to/main.css');

        $this->assetPipeline->requireCss('main');

        $cssAssets = $this->assetPipeline->allCss();

        $this->assertEquals('/path/to/main.css', $cssAssets->first());
    }

    /** @test */
    public function it_should_throw_an_exception_if_js_asset_not_found()
    {
        $this->expectException('Modules\Core\Foundation\Asset\AssetNotFoundException');

        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $this->assetPipeline->requireJs('app');
    }

    /** @test */
    public function it_should_throw_an_exception_if_css_asset_not_found()
    {
        $this->expectException('Modules\Core\Foundation\Asset\AssetNotFoundException');

        $this->assetManager->addAsset('main', '/path/to/main.css');

        $this->assetPipeline->requireCss('iCheck');
    }

    /** @test */
    public function it_should_place_js_asset_after_dependency()
    {
        $this->assetManager->addAsset('mega_slider', '/path/to/mega_slider.js');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');
        $this->assetManager->addAsset('jquery.iCheck', '/path/to/jquery_iCheck.js');
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('iCheck', '/path/to/iCheck.css');
        $this->assetManager->addAsset('bootstrap', '/path/to/bootstrap.css');

        $this->assetPipeline->requireJs('jquery');
        $this->assetPipeline->requireJs('mega_slider');
        $this->assetPipeline->requireJs('jquery_plugin')->after('jquery');
        $this->assetPipeline->requireJs('jquery.iCheck');

        $jsAssets = $this->assetPipeline->allJs();

        $expected = [
            'jquery' => '/path/to/jquery.js',
            'jquery_plugin' => '/path/to/jquery_plugin.js',
            'mega_slider' => '/path/to/mega_slider.js',
            'jquery.iCheck' => '/path/to/jquery_iCheck.js',
        ];
        $this->assertEquals($expected, $jsAssets->toArray());
    }

    /** @test */
    public function it_should_place_css_asset_after_dependency()
    {
        $this->assetManager->addAsset('mega_slider', '/path/to/mega_slider.js');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('iCheck', '/path/to/iCheck.css');
        $this->assetManager->addAsset('bootstrap', '/path/to/bootstrap.css');
        $this->assetManager->addAsset('datatables-css', '/path/to/datatables.css');

        $this->assetPipeline->requireCss('bootstrap');
        $this->assetPipeline->requireCss('iCheck');
        $this->assetPipeline->requireCss('main')->after('bootstrap');
        $this->assetPipeline->requireCss('datatables-css');

        $cssAssets = $this->assetPipeline->allCss();

        $expected = [
            'bootstrap' => '/path/to/bootstrap.css',
            'main' => '/path/to/main.css',
            'iCheck' => '/path/to/iCheck.css',
            'datatables-css' => '/path/to/datatables.css',
        ];
        $this->assertEquals($expected, $cssAssets->toArray());
    }

    /** @test */
    public function it_should_place_js_asset_before_dependency()
    {
        $this->assetManager->addAsset('mega_slider', '/path/to/mega_slider.js');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');
        $this->assetManager->addAsset('jquery.iCheck', '/path/to/jquery_iCheck.js');
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('iCheck', '/path/to/iCheck.css');
        $this->assetManager->addAsset('bootstrap', '/path/to/bootstrap.css');

        $this->assetPipeline->requireJs('jquery');
        $this->assetPipeline->requireJs('mega_slider');
        $this->assetPipeline->requireJs('jquery_plugin')->before('mega_slider');
        $this->assetPipeline->requireJs('jquery.iCheck');

        $jsAssets = $this->assetPipeline->allJs();

        $expected = [
            'jquery' => '/path/to/jquery.js',
            'mega_slider' => '/path/to/mega_slider.js',
            'jquery_plugin' => '/path/to/jquery_plugin.js',
            'jquery.iCheck' => '/path/to/jquery_iCheck.js',
        ];
        $this->assertEquals($expected, $jsAssets->toArray());
    }

    /** @test */
    public function it_should_place_css_asset_before_dependency()
    {
        $this->assetManager->addAsset('mega_slider', '/path/to/mega_slider.js');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('iCheck', '/path/to/iCheck.css');
        $this->assetManager->addAsset('bootstrap', '/path/to/bootstrap.css');
        $this->assetManager->addAsset('datatables-css', '/path/to/datatables.css');

        $this->assetPipeline->requireCss('bootstrap');
        $this->assetPipeline->requireCss('iCheck');
        $this->assetPipeline->requireCss('main')->before('iCheck');
        $this->assetPipeline->requireCss('datatables-css');

        $cssAssets = $this->assetPipeline->allCss();

        $expected = [
            'bootstrap' => '/path/to/bootstrap.css',
            'main' => '/path/to/main.css',
            'iCheck' => '/path/to/iCheck.css',
            'datatables-css' => '/path/to/datatables.css',
        ];
        $this->assertEquals($expected, $cssAssets->toArray());
    }

    /** @test */
    public function it_should_require_an_array_of_assets()
    {
        $this->assetManager->addAssets([
            'jquery' => '/path/to/jquery.js',
            'plugin' => '/path/to/plugin.js',
            'main' => '/path/to/main.css',
            'icheck' => '/path/to/icheck.css',
        ]);

        $this->assetPipeline->requireCss([
            'main',
            'icheck',
        ]);
        $this->assetPipeline->requireJs([
            'jquery',
            'plugin',
        ]);

        $cssAssets = $this->assetPipeline->allCss();
        $jsAssets = $this->assetPipeline->allJs();

        $expectedCss = [
            'main' => '/path/to/main.css',
            'icheck' => '/path/to/icheck.css',
        ];
        $expectedJs = [
            'jquery' => '/path/to/jquery.js',
            'plugin' => '/path/to/plugin.js',
        ];

        $this->assertEquals($expectedCss, $cssAssets->toArray());
        $this->assertEquals($expectedJs, $jsAssets->toArray());
    }
}
