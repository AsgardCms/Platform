<?php

namespace Modules\Workshop\Tests;

use Modules\Workshop\Scaffold\Theme\Exceptions\FileTypeNotFoundException;
use Modules\Workshop\Scaffold\Theme\Exceptions\ThemeExistsException;
use Modules\Workshop\Scaffold\Theme\ThemeScaffold;

class ThemeScaffoldTest extends BaseTestCase
{
    public $path = 'Themes';

    /**
     * @var ThemeScaffold
     */
    protected $scaffold;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var
     */
    protected $testThemeName;
    /**
     * @var
     */
    protected $testThemePath;

    public function setUp()
    {
        parent::setUp();
        $this->finder = $this->app['files'];
        $this->scaffold = $this->app['asgard.theme.scaffold'];
        if (!$this->finder->isDirectory(base_path("Themes"))) {
            $this->finder->makeDirectory(base_path("Themes"));
        }
        $this->testThemeName = 'TestingTheme';
        $this->testThemePath = base_path("Themes/{$this->testThemeName}");
    }

    private function generateFrontendTheme()
    {
        $this->scaffold->setName($this->testThemeName)->forType('frontend')->setVendor('asgardcms')->generate();
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->testThemePath);
        $this->finder->deleteDirectory(base_path("Themes"));
    }

    /** @test */
    public function it_generates_theme_folder()
    {
        $this->scaffold->setFiles([]);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isDirectory($this->testThemePath));
    }

    /** @test */
    public function it_throws_exception_if_file_type_does_not_exist()
    {
        $this->scaffold->setFiles(['OneTwoThree']);

        $this->expectException(FileTypeNotFoundException::class);

        $this->generateFrontendTheme();
    }

    /** @test */
    public function it_throws_exception_if_theme_exists()
    {
        $this->expectException(ThemeExistsException::class);

        $this->scaffold->setFiles([]);
        $this->generateFrontendTheme();
        $this->generateFrontendTheme();
    }

    /** @test */
    public function it_throws_exception_if_no_name_provided()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You must provide a name');

        $this->scaffold->setName('')->forType('frontend')->setVendor('asgardcms')->generate();
    }

    /** @test */
    public function it_throws_exception_if_no_type_provided()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You must provide a type');

        $this->scaffold->setName($this->testThemeName)->forType('')->setVendor('asgardcms')->generate();
    }

    /** @test */
    public function it_throws_exception_if_no_vendor_provided()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You must provide a vendor name');

        $this->scaffold->setName($this->testThemeName)->forType('frontend')->setVendor('')->generate();
    }

    /** @test */
    public function it_creates_theme_json_file()
    {
        $this->scaffold->setFiles(['themeJson']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isFile($this->testThemePath . '/theme.json'));
        $this->assertTrue(str_contains($this->finder->get($this->testThemePath . '/theme.json'), '"name": "' . $this->testThemeName . '",'));
        $this->assertTrue(str_contains($this->finder->get($this->testThemePath . '/theme.json'), '"type": "frontend"'));
    }

    /** @test */
    public function it_creates_composer_json_file()
    {
        $this->scaffold->setFiles(['composerJson']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isFile($this->testThemePath . '/composer.json'));
        $this->assertTrue(str_contains($this->finder->get($this->testThemePath . '/composer.json'), '"name": "asgardcms/TestingTheme-theme",'));
    }

    /** @test */
    public function it_creates_master_blade_layout()
    {
        $this->scaffold->setFiles(['masterBladeLayout']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isFile($this->testThemePath . '/views/layouts/master.blade.php'));
    }

    /** @test */
    public function it_creates_basic_view()
    {
        $this->scaffold->setFiles(['masterBladeLayout', 'basicView']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isFile($this->testThemePath . '/views/default.blade.php'));
    }

    /** @test */
    public function it_creates_empty_resources_folder()
    {
        $this->scaffold->setFiles(['resourcesFolder']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/resources'));
        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/resources/css'));
        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/resources/js'));
        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/resources/images'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/resources/.gitignore'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/resources/css/.gitignore'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/resources/js/.gitignore'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/resources/images/.gitignore'));
    }

    /** @test */
    public function it_creates_empty_assets_folder()
    {
        $this->scaffold->setFiles(['assetsFolder']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/assets'));
        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/assets/css'));
        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/assets/js'));
        $this->assertTrue($this->finder->isDirectory($this->testThemePath . '/assets/images'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/assets/.gitignore'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/assets/css/.gitignore'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/assets/js/.gitignore'));
        $this->assertTrue($this->finder->isFile($this->testThemePath . '/assets/images/.gitignore'));
    }

    /** @test */
    public function it_has_default_version_in_theme_json_file()
    {
        $this->scaffold->setFiles(['themeJson']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isFile($this->testThemePath . '/theme.json'));
        $this->assertTrue(str_contains($this->finder->get($this->testThemePath . '/theme.json'), '"version": "1.0.0"'));
    }
}
