<?php

namespace Modules\Media\Tests;

use Modules\Media\Entities\File;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Services\Movers\FolderMover;

final class FolderMoverTest extends MediaTestCase
{
    /**
     * @var FolderMover
     */
    private $mover;
    /**
     * @var FolderRepository
     */
    private $folder;

    protected function setUp()
    {
        parent::setUp();

        $this->resetDatabase();

        $this->mover = app(FolderMover::class);
        $this->folder = app(FolderRepository::class);
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    public function tearDown()
    {
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    /** @test */
    public function it_can_move_folder_on_disk()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Future Child folder']);

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/future-child-folder')));
        $this->mover->move($folderTwo, $folder);
        $this->assertTrue(
            $this->app['files']->isDirectory(public_path('/assets/media/my-folder/future-child-folder')),
            'Folder was not moved'
        );
    }

    /** @test */
    public function it_can_move_folder_in_database()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Future Child folder']);

        $this->assertEquals('/assets/media/future-child-folder', $folderTwo->path->getRelativeUrl());
        $this->mover->move($folderTwo, $folder);
        $this->assertEquals('/assets/media/my-folder/future-child-folder', $folderTwo->path->getRelativeUrl());
    }

    /** @test */
    public function it_can_move_folder_with_folders_and_files_in_it_database()
    {
        $mainFolder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Second folder']);
        $folderThird = $this->folder->create(['name' => 'Third folder', 'parent_id' => $folderTwo->id]);
        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folderTwo->id);
        $fileTwo = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-other-file.jpg'), $folderThird->id);

        $this->assertEquals('/assets/media/second-folder', $folderTwo->path->getRelativeUrl());
        $this->assertEquals('/assets/media/second-folder/third-folder', $folderThird->path->getRelativeUrl());
        $this->assertEquals('/assets/media/second-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/second-folder/third-folder/my-other-file.jpg', $fileTwo->path->getRelativeUrl());

        $this->mover->move($folderTwo, $mainFolder);

        $folderTwo->refresh();
        $folderThird->refresh();
        $file->refresh();
        $fileTwo->refresh();
        $this->assertEquals('/assets/media/my-folder/second-folder', $folderTwo->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-folder/second-folder/third-folder', $folderThird->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-folder/second-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-folder/second-folder/third-folder/my-other-file.jpg', $fileTwo->path->getRelativeUrl());
    }

    /** @test */
    public function it_can_move_folder_back_to_root_folder()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $this->folder->create(['name' => 'Child Folder', 'parent_id' => $parentFolder->id]);

        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folder->id);

        $this->assertEquals('/assets/media/my-folder/child-folder', $folder->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-folder/child-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/my-folder/child-folder')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/child-folder/my-file.jpg')));

        $this->mover->move($folder, $this->makeRootFolder());

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/child-folder')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/child-folder/my-file.jpg')));
        $this->assertEquals('/assets/media/child-folder', $folder->path->getRelativeUrl());
        $file->refresh();
        $this->assertEquals('/assets/media/child-folder/my-file.jpg', $file->path->getRelativeUrl());
    }

    /** @test */
    public function it_can_move_folder_with_folders_and_files_in_it_disk()
    {
        $mainFolder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Second folder']);
        $folderThird = $this->folder->create(['name' => 'Third folder', 'parent_id' => $folderTwo->id]);
        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folderTwo->id);
        $fileTwo = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-other-file.jpg'), $folderThird->id);

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/second-folder')));
        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/second-folder/third-folder')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/second-folder/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/second-folder/third-folder/my-other-file.jpg')));

        $this->mover->move($folderTwo, $mainFolder);

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/my-folder/second-folder')));
        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/my-folder/second-folder/third-folder')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/second-folder/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/second-folder/third-folder/my-other-file.jpg')));
    }

    /** @test */
    public function it_does_not_move_folder_if_folder_name_exists_at_location()
    {
        $mainFolder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Child Folder']);
        $folderThird = $this->folder->create(['name' => 'Child Folder', 'parent_id' => $mainFolder->id]);

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/my-folder/child-folder')));

        $this->mover->move($folderTwo, $mainFolder);

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/child-folder')));
        $this->assertEquals('/assets/media/child-folder', $folderTwo->path->getRelativeUrl());
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
