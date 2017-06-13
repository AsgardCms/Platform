<?php

namespace Modules\Core\Tests\Asset;

use Modules\Core\Foundation\Asset\Types\AssetType;
use Modules\Core\Foundation\Asset\Types\AssetTypeFactory;
use Modules\Core\Tests\BaseTestCase;

class AssetFactoryTest extends BaseTestCase
{
    /**
     * @var AssetTypeFactory
     */
    private $assetFactory;

    public function setUp()
    {
        parent::__construct();
        $this->refreshApplication();
        $this->assetFactory = app(AssetTypeFactory::class);
    }

    /** @test */
    public function it_throws_exception_if_asset_type_not_found()
    {
        $this->expectException(\InvalidArgumentException::class);

        $path = ['somehting' => 'some/path.something'];

        $this->assetFactory->make($path)->url();
    }

    /** @test */
    public function it_returns_an_asset_type_class()
    {
        $themePath = ['theme' => 'some/path.js'];
        $modulePath = ['module' => 'some/path.js'];
        $cdnPath = ['module' => 'some/path.js'];

        $themeClass = $this->assetFactory->make($themePath);
        $moduleClass = $this->assetFactory->make($modulePath);
        $cdnClass = $this->assetFactory->make($cdnPath);

        $this->assertInstanceOf(AssetType::class, $themeClass);
        $this->assertInstanceOf(AssetType::class, $moduleClass);
        $this->assertInstanceOf(AssetType::class, $cdnClass);
    }

    /** @test */
    public function it_returns_theme_asset()
    {
        $themePath = ['theme' => 'some/path.js'];

        $asset = $this->assetFactory->make($themePath)->url();

        $this->assertEquals('http://localhost/some/path.js', $asset);
    }

    /** @test */
    public function it_returns_module_asset()
    {
        $modulePath = ['module' => 'core:some/path.js'];

        $asset = $this->assetFactory->make($modulePath)->url();

        $this->assertEquals('//localhost/modules/core/some/path.js', $asset);
    }

    /** @test */
    public function it_returns_cdn_asset()
    {
        $cdnPath = ['cdn' => 'https://js.stripe.com/v2'];

        $asset = $this->assetFactory->make($cdnPath)->url();

        $this->assertEquals('https://js.stripe.com/v2', $asset);
    }
}
