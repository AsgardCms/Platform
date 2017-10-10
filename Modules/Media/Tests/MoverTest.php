<?php

namespace Modules\Media\Tests;

use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Services\Movers\Mover;

final class MoverTest extends MediaTestCase
{
    /**
     * @var Mover
     */
    private $mover;

    public function setUp()
    {
        parent::setUp();

        $this->resetDatabase();

        $this->mover = app(Mover::class);
        //$this->file = app(FileRepository::class);
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    public function tearDown()
    {
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    /** @test */
    public function it_detects_its_a_file_to_move()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);

        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->create('my-file.pdf'));

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.pdf')));
        $failedAmount = $this->mover->move($file, $parentFolder);
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-file.pdf')));
        $this->assertSame(0, $failedAmount);
    }

    /** @test */
    public function it_detects_its_a_folder_to_move()
    {
        $folderRepository = app(FolderRepository::class);
        $parentFolder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $childFolder = $folderRepository->create(['name' => 'Child Folder', 'parent_id' => 0]);

        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/child-folder')));
        $failedAmount = $this->mover->move($childFolder, $parentFolder);
        $this->assertTrue($this->app['files']->isDirectory(public_path('/assets/media/my-folder/child-folder')));
        $this->assertSame(0, $failedAmount);
    }

    /** @test */
    public function it_counts_amount_of_failed_moves()
    {
        $folderRepository = app(FolderRepository::class);
        $folder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folder->id);
        $fileTwo = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-file.jpg')));
        $this->assertEquals('/assets/media/my-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-file.jpg', $fileTwo->path->getRelativeUrl());

        $failedAmount = $this->mover->move($fileTwo, $folder);

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-file.jpg')));
        $this->assertEquals('/assets/media/my-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-file.jpg', $fileTwo->path->getRelativeUrl());
        $this->assertSame(1, $failedAmount);
    }
}
