<?php

namespace Modules\Media\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Media\Entities\File;
use Modules\Media\Events\FolderIsCreating;
use Modules\Media\Events\FolderIsDeleting;
use Modules\Media\Events\FolderIsUpdating;
use Modules\Media\Events\FolderWasCreated;
use Modules\Media\Events\FolderWasUpdated;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Support\Collection\NestedFoldersCollection;

final class EloquentFolderRepositoryTest extends MediaTestCase
{
    /**
     * @var FolderRepository
     */
    private $folder;

    public function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();

        $this->folder = app(FolderRepository::class);
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    public function tearDown(): void
    {
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    /** @test */
    public function it_can_create_a_folder_in_database()
    {
        $folder = $this->folder->create(['name' => 'My Folder', 'parent_id' => 0]);

        $this->assertCount(1, $this->folder->all());
        $this->assertEquals('My Folder', $folder->filename);
        $this->assertEquals('/assets/media/my-folder', $folder->path->getRelativeUrl());
        $this->assertTrue($folder->is_folder);
        $this->assertTrue($folder->isFolder());
        $this->assertEquals(0, $folder->folder_id);
    }

    /** @test */
    public function it_triggers_event_on_created_folder()
    {
        Event::fake();

        $folder = $this->folder->create(['name' => 'My Folder']);

        Event::assertDispatched(FolderWasCreated::class, function ($e) use ($folder) {
            return $e->folder->id === $folder->id;
        });
    }

    /** @test */
    public function it_triggers_an_event_when_folder_is_creating()
    {
        Event::fake();

        $folder = $this->folder->create(['name' => 'My Folder']);

        Event::assertDispatched(FolderIsCreating::class, function ($e) use ($folder) {
            return $e->getAttribute('filename') === $folder->filename;
        });
    }

    /** @test */
    public function it_can_change_folder_data_before_creating_folder()
    {
        Event::listen(FolderIsCreating::class, function (FolderIsCreating $event) {
            $filename = $event->getAttribute('filename');
            $event->setAttributes(['filename' => strtoupper($filename)]);
        });

        $folder = $this->folder->create(['name' => 'My Folder']);

        $this->assertEquals('MY FOLDER', $folder->filename);
    }

    /** @test */
    public function it_can_create_folder_on_disk()
    {
        $this->folder->create(['name' => 'My Folder']);

        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/my-folder')));
    }

    /** @test */
    public function it_can_find_a_folder()
    {
        $this->folder->create(['name' => 'My Folder']);

        $folder = $this->folder->findFolder(1);

        $this->assertInstanceOf(File::class, $folder);
        $this->assertEquals(1, $folder->id);
    }

    /** @test */
    public function it_can_create_folders_belonging_to_another_folder()
    {
        $this->folder->create(['name' => 'Root Folder']);
        $childFolder = $this->folder->create(['name' => 'Child folder', 'parent_id' => 1]);

        $this->assertEquals('/assets/media/root-folder/child-folder', $childFolder->path->getRelativeUrl());
        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/root-folder/child-folder')));
    }

    /** @test */
    public function it_can_update_a_folder_in_database()
    {
        $folder = $this->folder->create(['name' => 'My Folder', 'parent_id' => 0]);

        $folder = $this->folder->update($folder, ['name' => 'New Name!']);

        $this->assertCount(1, $this->folder->all());
        $this->assertEquals('New Name!', $folder->filename);
        $this->assertEquals('/assets/media/new-name', $folder->path->getRelativeUrl());
    }

    /** @test */
    public function it_can_update_sub_folder_in_database()
    {
        $folder = $this->folder->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folderTwo = $this->folder->create(['name' => 'Child Folder', 'parent_id' => $folder->id]);

        $folderTwo = $this->folder->update($folderTwo, ['name' => 'Awesome Child', 'parent_id' => $folder->id]);

        $this->assertEquals('Awesome Child', $folderTwo->filename);
        $this->assertEquals('/assets/media/my-folder/awesome-child', $folderTwo->path->getRelativeUrl());
    }

    /** @test */
    public function it_triggers_event_when_folder_was_updated()
    {
        Event::fake();

        $folder = $this->folder->create(['name' => 'My Folder', 'parent_id' => 0]);
        $folder = $this->folder->update($folder, ['name' => 'New Name!']);

        Event::assertDispatched(FolderWasUpdated::class, function ($e) use ($folder) {
            return $e->folder->id === $folder->id;
        });
    }

    /** @test */
    public function it_triggers_an_event_when_folder_is_updating()
    {
        Event::fake();

        $folder = $this->folder->create(['name' => 'My Folder']);
        $folder = $this->folder->update($folder, ['name' => 'New Name!']);

        Event::assertDispatched(FolderIsUpdating::class, function ($e) use ($folder) {
            return $e->getAttribute('filename') === $folder->filename;
        });
    }

    /** @test */
    public function it_can_change_data_when_folder_is_updating()
    {
        Event::listen(FolderIsUpdating::class, function (FolderIsUpdating $event) {
            $filename = $event->getAttribute('filename');
            $event->setAttributes(['filename' => strtoupper($filename)]);
        });

        $folder = $this->folder->create(['name' => 'My Folder']);
        $folder = $this->folder->update($folder, ['name' => 'New Name!']);

        $this->assertEquals('NEW NAME!', $folder->filename);
    }

    /** @test */
    public function it_can_rename_folder_on_disk()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);
        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/my-folder')));

        $folder = $this->folder->update($folder, ['name' => 'New Name!']);
        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/new-name')));
    }

    /** @test */
    public function it_can_rename_folder_with_content()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');
        app(FileService::class)->store($file, $folder->id);

        $folder = $this->folder->update($folder, ['name' => 'New Name!']);
        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/new-name')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/new-name/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/new-name/my-file_smallThumb.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/new-name/my-file_mediumThumb.jpg')));
    }

    /** @test */
    public function it_renames_folder_and_database_references_to_that_folder()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Child folder', 'parent_id' => $parentFolder->id]);
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');
        $fileTwo = \Illuminate\Http\UploadedFile::fake()->image('my-second-file.jpg');
        $fileThree = \Illuminate\Http\UploadedFile::fake()->image('my-third-file.jpg');
        $fileOne = app(FileService::class)->store($file, $parentFolder->id);
        $fileTwo = app(FileService::class)->store($fileTwo, $parentFolder->id);
        $fileThree = app(FileService::class)->store($fileThree, $folderTwo->id);

        $parentFolder = $this->folder->update($parentFolder, ['name' => 'New Name!']);
        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/new-name')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/new-name/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('assets/media/new-name/child-folder/my-third-file.jpg')));

        $fileOne->refresh();
        $fileTwo->refresh();
        $fileThree->refresh();
        $this->assertEquals('/assets/media/new-name/my-file.jpg', $fileOne->path->getRelativeUrl());
        $this->assertEquals('/assets/media/new-name/my-second-file.jpg', $fileTwo->path->getRelativeUrl());
        $this->assertEquals('/assets/media/new-name/child-folder/my-third-file.jpg', $fileThree->path->getRelativeUrl());
    }

    /** @test */
    public function it_can_find_all_folders()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder']);
        $this->folder->create(['name' => 'Child folder', 'parent_id' => $parentFolder->id]);
        $this->createFile();
        $this->createFile('second-file.jpg');

        $this->assertCount(2, $this->folder->all());
    }

    /** @test */
    public function it_can_find_all_folders_with_nested()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder']);
        $this->folder->create(['name' => 'Child folder', 'parent_id' => $parentFolder->id]);
        $this->createFile();
        $this->createFile('second-file.jpg');

        $folders = $this->folder->allNested();
        $this->assertInstanceOf(NestedFoldersCollection::class, $folders);
        $this->assertCount(2, $folders);
    }

    /** @test */
    public function it_can_delete_a_folder_from_database()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);

        $this->assertCount(1, $this->folder->all());
        $this->folder->destroy($folder);
        $this->assertCount(0, $this->folder->all());
    }

    /** @test */
    public function it_triggers_event_when_folder_is_deleting()
    {
        Event::fake();

        $folder = $this->folder->create(['name' => 'My Folder']);
        $this->folder->destroy($folder);

        Event::assertDispatched(FolderIsDeleting::class, function ($e) use ($folder) {
            return $e->folder->id === $folder->id;
        });
    }

    /** @test */
    public function it_deletes_folder_from_disk()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);

        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/my-folder')));
        $this->folder->destroy($folder);
        $this->assertFalse($this->app['files']->isDirectory(public_path('assets/media/my-folder')));
    }

    /** @test */
    public function it_deletes_folder_in_subfolder_on_disk()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder']);
        $folder = $this->folder->create(['name' => 'Subfolder', 'parent_id' => $parentFolder->id]);

        $this->assertTrue($this->app['files']->isDirectory(public_path('assets/media/my-folder/subfolder')));
        $this->folder->destroy($folder);
        $this->assertFalse($this->app['files']->isDirectory(public_path('assets/media/my-folder/subfolder')));
    }

    /** @test */
    public function it_can_remove_folder_with_files()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Child folder', 'parent_id' => $parentFolder->id]);
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');
        $fileTwo = \Illuminate\Http\UploadedFile::fake()->image('my-second-file.jpg');
        $fileThree = \Illuminate\Http\UploadedFile::fake()->image('my-third-file.jpg');
        app(FileService::class)->store($file, $parentFolder->id);
        app(FileService::class)->store($fileTwo, $folderTwo->id);
        app(FileService::class)->store($fileThree, $folderTwo->id);

        $this->folder->destroy($folderTwo);
        $this->assertFalse($this->app['files']->isDirectory(public_path('assets/media/my-folder/child-folder')));
    }

    /** @test */
    public function it_removes_folder_and_files_from_database()
    {
        $parentFolder = $this->folder->create(['name' => 'My Folder']);
        $folderTwo = $this->folder->create(['name' => 'Child folder', 'parent_id' => $parentFolder->id]);
        $file = \Illuminate\Http\UploadedFile::fake()->image('my-file.jpg');
        $fileTwo = \Illuminate\Http\UploadedFile::fake()->image('my-second-file.jpg');
        $fileThree = \Illuminate\Http\UploadedFile::fake()->image('my-third-file.jpg');
        app(FileService::class)->store($file, $parentFolder->id);
        app(FileService::class)->store($fileTwo, $folderTwo->id);
        app(FileService::class)->store($fileThree, $folderTwo->id);

        $this->assertCount(5, File::all());
        $this->folder->destroy($folderTwo);
        $this->assertCount(2, File::all());
    }

    /** @test */
    public function it_finds_a_folder_or_returns_a_root_folder()
    {
        $this->folder->create(['name' => 'My Folder']);

        $foundFolder = $this->folder->findFolderOrRoot(1);
        $this->assertInstanceOf(File::class, $foundFolder);
        $this->assertEquals('My Folder', $foundFolder->filename);
        $this->assertTrue($foundFolder->exists);
        $notFolder = $this->folder->findFolderOrRoot(0);
        $this->assertFalse($notFolder->exists);
        $this->assertEquals(0, $notFolder->id);
        $this->assertEquals(0, $notFolder->folder_id);
        $this->assertEquals(config('asgard.media.config.files-path'), $notFolder->path->getRelativeUrl());
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
