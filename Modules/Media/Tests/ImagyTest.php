<?php

namespace Modules\Media\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\Intervention\InterventionFactory;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Modules\Media\ValueObjects\MediaPath;

class ImagyTest extends MediaTestCase
{
    /**
     * @var Imagy
     */
    protected $imagy;
    /**
     * @var Filesystem
     */
    protected $finder;
    /**
     * @var Repository
     */
    protected $config;
    /**
     * @var string
     */
    protected $mediaPath;
    private $testbenchPublicPath;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
        $this->config = App::make(Repository::class);
        $this->finder = App::make(Filesystem::class);
        $this->imagy = new Imagy(new InterventionFactory(), app(ThumbnailManager::class), $this->config);

        $this->testbenchPublicPath = __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel/public/';
        $this->mediaPath = __DIR__ . '/Fixtures/';
        $this->finder->copy("{$this->mediaPath}google-map.png", "{$this->testbenchPublicPath}google-map.png");
    }

    public function tearDown()
    {
        $this->finder->delete("{$this->testbenchPublicPath}google-map.png");
        $this->finder->delete("{$this->testbenchPublicPath}google-map_smallThumb.png");
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    public function it_should_create_a_file()
    {
        $path = new MediaPath("/google-map.png");
        $this->imagy->get($path, 'smallThumb', true);

        $this->assertTrue($this->finder->isFile("{$this->testbenchPublicPath}google-map_smallThumb.png"));
    }

    /** @test */
    public function it_should_not_create_thumbs_for_pdf_files()
    {
        $this->imagy->get("{$this->mediaPath}test-pdf.pdf", 'smallThumb', true);

        $this->assertFalse($this->finder->isFile(public_path() . "{$this->mediaPath}test-pdf_smallThumb.png"));
    }

    /** @test */
    public function it_should_return_thumbnail_path()
    {
        $this->resetDatabase();

        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $file = app(FileService::class)->store($file);

        $expected = config('app.url') . config('asgard.media.config.files-path') . 'my-file_smallThumb.jpg';
        $path = $this->imagy->getThumbnail($file->path, 'smallThumb');

        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-file.jpg')));
        $this->assertEquals($expected, $path);
    }

    /** @test */
    public function it_should_return_thumbnail_path_to_sub_folders()
    {
        $this->resetDatabase();

        app(FolderRepository::class)->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $file = app(FileService::class)->store($file, 1);

        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-folder/my-file.jpg')));

        $smallThumbPath = $this->imagy->getThumbnail($file->path, 'smallThumb');

        $expected = config('app.url') . config('asgard.media.config.files-path') . 'my-folder/my-file_smallThumb.jpg';

        $this->assertEquals($expected, $smallThumbPath);
    }

    /** @test */
    public function it_should_return_same_path_for_non_images()
    {
        $path = $this->imagy->getThumbnail("{$this->mediaPath}test-pdf.pdf", 'smallThumb');
        $expected = "{$this->mediaPath}test-pdf.pdf";

        $this->assertEquals($expected, $path);
    }

    /** @test */
    public function it_should_detect_an_image()
    {
        $jpg = $this->imagy->isImage('image.jpg');
        $png = $this->imagy->isImage('image.png');
        $pdf = $this->imagy->isImage('pdf.pdf');

        $this->assertTrue($jpg);
        $this->assertTrue($png);
        $this->assertFalse($pdf);
    }
}
