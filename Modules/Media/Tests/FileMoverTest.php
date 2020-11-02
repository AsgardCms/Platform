<?php

namespace Modules\Media\Tests;

use Modules\Media\Entities\File;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Services\Movers\FileMover;

final class FileMoverTest extends MediaTestCase
{
    /**
     * @var FileMover
     */
    private $mover;
    /**
     * @var FileRepository
     */
    private $file;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();

        $this->mover = app(FileMover::class);
        $this->file = app(FileRepository::class);
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    public function tearDown(): void
    {
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    /** @test */
    public function it_can_move_file_on_disk()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $folderRepository->create(['name' => 'Child Folder', 'parent_id' => $parentFolder->id]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('my-file.pdf');

        $file = app(FileService::class)->store($file);
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.pdf')));

        $this->mover->move($file, $folder);
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file.pdf')));
    }

    /** @test */
    public function it_can_move_a_file_database()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $folderRepository->create(['name' => 'Child Folder', 'parent_id' => $parentFolder->id]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('my-file.pdf');
        $file = app(FileService::class)->store($file);

        $this->mover->move($file, $folder);

        $this->assertEquals('my-file.pdf', $file->filename);
        $this->assertEquals($file->folder_id, $folder->id);
        $this->assertEquals('/assets/media/my-folder/child-folder/my-file.pdf', $file->path->getRelativeUrl());
    }

    /** @test */
    public function it_can_move_file_with_thumbnails_on_disk()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $folderRepository->create(['name' => 'Child Folder', 'parent_id' => $parentFolder->id]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $file = app(FileService::class)->store($file);
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));

        $this->mover->move($file, $folder);

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file_smallThumb.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file_mediumThumb.jpg')));
    }

    /** @test */
    public function it_can_move_file_back_to_root_folder()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $folderRepository->create(['name' => 'Child Folder', 'parent_id' => $parentFolder->id]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('my-file.pdf');

        $file = app(FileService::class)->store($file, $folder->id);
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file.pdf')));

        $this->mover->move($file, $this->makeRootFolder());
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.pdf')));
    }

    /** @test */
    public function it_can_move_file_with_thumbnails_back_to_root_folder()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $folderRepository->create(['name' => 'Child Folder', 'parent_id' => $parentFolder->id]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');

        $file = app(FileService::class)->store($file, $folder->id);
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file.jpg')));

        $this->mover->move($file, $this->makeRootFolder());
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file_smallThumb.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file_mediumThumb.jpg')));
    }

    /** @test */
    public function it_does_not_move_file_if_file_name_exists_at_location()
    {
        $folderRepository = app(FolderRepository::class);
        $folder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folder->id);
        $fileTwo = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-file.jpg')));
        $this->assertEquals('/assets/media/my-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-file.jpg', $fileTwo->path->getRelativeUrl());

        $this->mover->move($fileTwo, $folder);

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-file.jpg')));
        $this->assertEquals('/assets/media/my-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-file.jpg', $fileTwo->path->getRelativeUrl());
    }

    private function makeRootFolder() : File
    {
        return new File([
            'id' => 0,
            'folder_id' => 0,
            'path' => config('asgard.media.config.files-path'),
        ]);
    }
}
