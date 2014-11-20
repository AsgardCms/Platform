<?php  namespace Modules\Media\Tests;

use Illuminate\Support\Facades\App;
use Modules\Core\Tests\BaseTestCase;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\Intervention\InterventionFactory;
use Modules\Media\Image\ThumbnailsManager;

class ImagyTest extends BaseTestCase
{
    /**
     * @var Imagy
     */
    protected $imagy;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;
    /**
     * @var string
     */
    protected $mediaPath;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->config = App::make('Illuminate\Contracts\Config\Repository');
        $module = App::make('modules');
        $this->finder = App::make('Illuminate\Filesystem\Filesystem');
        $this->imagy = new Imagy(new InterventionFactory, new ThumbnailsManager($this->config, $module), $this->config);

        $this->mediaPath = $this->config->get('media::config.files-path');
    }

    /** @test */
    public function it_should_create_a_file()
    {
        if ($this->finder->isFile(public_path() . "{$this->mediaPath}google-map_smallThumb.png")) {
            $this->finder->delete(public_path() . "{$this->mediaPath}google-map_smallThumb.png");
        }

        $this->imagy->get("{$this->mediaPath}google-map.png", 'smallThumb', true);

        $this->assertTrue($this->finder->isFile(public_path() . "{$this->mediaPath}google-map_smallThumb.png"));
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
        $path = $this->imagy->getThumbnail("{$this->mediaPath}google-map.png", 'smallThumb');
        $expected = "{$this->mediaPath}google-map_smallThumb.png";

        $this->assertEquals($expected, $path);
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
