<?php

namespace Modules\Media\Tests;

use Modules\Media\Entities\File;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\ValueObjects\MediaPath;

class FileTest extends MediaTestCase
{
    /**
     * @var FileRepository
     */
    private $file;

    public function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();

        $this->file = app(FileRepository::class);
    }

    /** @test */
    public function it_creates_a_file()
    {
        $this->createFile('my/file/name.jpg');

        $this->assertCount(1, $this->file->all());
    }

    /** @test */
    public function it_should_return_media_path_value_object_on_path_attribtue()
    {
        $file = $this->createFile('my/file/name.jpg');

        $this->assertInstanceOf(MediaPath::class, $file->path);
    }

    /** @test */
    public function it_should_cast_the_path_value_object_to_string()
    {
        $file = $this->createFile('my/file/name.jpg');

        $this->assertEquals('http://localhost/my/file/name.jpg', $file->path_string);
    }

    /** @test */
    public function it_should_guess_the_media_type_of_object()
    {
        $file = $this->createFile('my/file/name.jpg');

        $this->assertEquals('image', $file->media_type);
    }

    /** @test */
    public function it_can_check_if_file_is_an_image()
    {
        $this->assertTrue($this->createFile('my/file/name.jpg')->isImage());
        $this->assertTrue($this->createFile('my/file/name.png')->isImage());
        $this->assertTrue($this->createFile('my/file/name.jpeg')->isImage());
        $this->assertTrue($this->createFile('my/file/name.gif')->isImage());
        $this->assertFalse($this->createFile('my/file/name.pdf')->isImage());
        $this->assertFalse($this->createFile('my/file/name.doc')->isImage());
    }

    /** @test */
    public function it_can_get_the_thumbnail()
    {
        $file = $this->createFile('my/file/name.jpg');

        $this->assertEquals('http://localhost/my/file/name_smallThumb.jpg', $file->getThumbnail('smallThumb'));
    }

    /** @test */
    public function it_wont_get_thumbnail_of_non_image_file()
    {
        $file = $this->createFile('my/file/name.pdf');

        $this->assertFalse($file->getThumbnail('smallThumb'));
    }

    private function createFile($fileName = 'random/name.jpg')
    {
        return File::create([
            'filename' => $fileName,
            'path' => config('asgard.media.config.files-path') . $fileName,
            'extension' => substr(strrchr($fileName, "."), 1),
            'mimetype' => 'image/jpg',
            'filesize' => '1024',
            'folder_id' => 0,
        ]);
    }
}
