<?php

namespace Modules\Media\Tests;

use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;

final class FileServiceTest extends MediaTestCase
{
    /**
     * @var FileService
     */
    private $fileService;

    public function setUp(): void
    {
        parent::setUp();
        $this->resetDatabase();
        $this->fileService = app(FileService::class);
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    public function tearDown(): void
    {
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    /** @test */
    public function it_creates_a_file_on_disk()
    {
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $this->fileService->store($file);

        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-file.jpg')));
    }

    /** @test */
    public function it_creates_file_thumbnails_on_disk()
    {
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $this->fileService->store($file);

        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-file_smallThumb.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-file_mediumThumb.jpg')));
    }

    /** @test */
    public function it_doesnt_create_thumbnails_for_non_images()
    {
        $file = \Illuminate\Http\UploadedFile::fake()->create('records.pdf');

        $this->fileService->store($file);

        $this->assertTrue($this->app['files']->exists(public_path('assets/media/records.pdf')));
        $this->assertFalse($this->app['files']->exists(public_path('assets/media/records_smallThumb.pdf')));
        $this->assertFalse($this->app['files']->exists(public_path('assets/media/records_mediumThumb.pdf')));
    }

    /** @test */
    public function it_can_store_a_file_in_sub_folder()
    {
        $folderRepository = app(FolderRepository::class);
        $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = \Illuminate\Http\UploadedFile::fake()->create('records.pdf');

        $this->fileService->store($file, 1);

        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-folder/records.pdf')));
    }

    /** @test */
    public function it_can_store_an_image_with_thumbnails_in_sub_folder()
    {
        $folderRepository = app(FolderRepository::class);
        $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $this->fileService->store($file, 1);

        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-folder/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-folder/my-file_smallThumb.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/my-folder/my-file_mediumThumb.jpg')));
    }
}
