<?php

namespace Modules\Media\Tests;

use Illuminate\Support\Facades\Event;
use Mockery;
use Modules\Media\Entities\File;
use Modules\Media\Events\FileIsCreating;
use Modules\Media\Events\FileIsUpdating;
use Modules\Media\Events\FileWasCreated;
use Modules\Media\Events\FileWasUpdated;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EloquentFileRepositoryTest extends MediaTestCase
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
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    public function tearDown(): void
    {
        if ($this->app['files']->isDirectory(public_path('assets')) === true) {
            $this->app['files']->deleteDirectory(public_path('assets'));
        }
    }

    /** @test */
    public function it_can_update_file_info()
    {
        $file = $this->createFile();

        $this->file->update($file, [
            'en' => [
                'description' => 'My description',
                'alt_attribute' => 'My alt attribute',
                'keywords' => 'keyword1, keyword2',
            ],
        ]);

        $file = $this->file->find(1);

        $this->assertEquals('My description', $file->description);
        $this->assertEquals('My alt attribute', $file->alt_attribute);
        $this->assertEquals('keyword1, keyword2', $file->keywords);
    }

    /** @test */
    public function it_can_create_file_from_uploadedfile()
    {
        $uploadedFile = Mockery::mock(UploadedFile::class);
        $fileInfo = Mockery::mock(SplFileInfo::class);

        $fileInfo->shouldReceive('getSize')
            ->andReturn(1024)
            ->once();

        $uploadedFile->shouldReceive('getClientOriginalName')
            ->andReturn('my-file.jpg')
            ->once();
        $uploadedFile->shouldReceive('getClientMimeType')
            ->andReturn('image/jpg')
            ->once();
        $uploadedFile->shouldReceive('getFileInfo')
            ->andReturn($fileInfo)
            ->once();

        $this->file->createFromFile($uploadedFile);

        $file = $this->file->find(1);

        $this->assertCount(1, $this->file->all());
        $this->assertEquals('my-file.jpg', $file->filename);
        $this->assertEquals('jpg', $file->extension);
        $this->assertEquals('image/jpg', $file->mimetype);
        $this->assertEquals('1024', $file->filesize);
    }

    /** @test */
    public function it_can_increment_file_name_version_if_name_exists()
    {
        $this->createFile('my-file.jpg');

        sleep(1);
        $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));
        sleep(1);
        $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));

        $this->assertEquals('my-file_1.jpg', $this->file->find(2)->filename);
        $this->assertEquals('my-file_2.jpg', $this->file->find(3)->filename);
    }

    /** @test */
    public function it_can_increment_file_name_version_to_a_number_higher_than_any_existing()
    {
        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));
        $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));
        $this->file->destroy($file);
        sleep(1);
        $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));
        sleep(1);
        $fileAgain = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));

        $this->assertEquals('my-file_2.jpg', $fileAgain->filename);
    }

    /** @test */
    public function it_can_delete_a_file()
    {
        $file = $this->createFile();

        $this->file->destroy($file);

        $this->assertCount(0, $this->file->all());
    }

    /** @test */
    public function it_triggers_event_when_file_was_created()
    {
        Event::fake();
        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('myfile.jpg'));

        Event::assertDispatched(FileWasCreated::class, function ($e) use ($file) {
            return $e->file->id === $file->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_file_is_creating()
    {
        Event::fake();

        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('myfile.jpg'));

        Event::assertDispatched(FileIsCreating::class, function ($e) use ($file) {
            return $e->getAttribute('filename') === $file->filename;
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(FileIsCreating::class, function (FileIsCreating $event) {
            $event->setAttributes(['filename' => 'imabettername.jpg']);
        });

        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('myfile.jpg'));

        $this->assertEquals('imabettername.jpg', $file->filename);
    }

    /** @test */
    public function it_triggers_event_when_file_was_updated()
    {
        Event::fake();

        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('myfile.jpg'));
        $this->file->update($file, []);

        Event::assertDispatched(FileWasUpdated::class, function ($e) use ($file) {
            return $e->file->id === $file->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_file_is_updating()
    {
        Event::fake();

        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('myfile.jpg'));
        $this->file->update($file, [
            'en' => [
                'description' => 'My cool file!',
                'alt_attribute' => 'My cool file!',
                'keywords' => 'My cool file!',
            ],
        ]);

        Event::assertDispatched(FileIsUpdating::class, function ($e) use ($file) {
            return $e->getFile()->id === $file->id &&
                $e->getAttribute('en.description') === 'My cool file!';
        });
    }

    /** @test */
    public function it_can_change_properties_before_update()
    {
        Event::listen(FileIsUpdating::class, function (FileIsUpdating $event) {
            $event->setAttributes([
                'filename' => 'bettername.jpg',
                'en' => [
                    'description' => 'Hello World',
                ],
            ]);
        });

        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('myfile.jpg'));
        $this->file->update($file, [
            'en' => [
                'description' => 'My cool file!',
                'alt_attribute' => 'My cool file!',
                'keywords' => 'My cool file!',
            ],
        ]);

        $this->assertEquals('bettername.jpg', $file->filename);
        $this->assertEquals('Hello World', $file->translate('en')->description);
    }

    /** @test */
    public function it_can_create_a_file_in_a_folder()
    {
        $folder = app(FolderRepository::class)->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folder->id);

        $this->assertCount(2, $this->file->all());

        $this->assertEquals('/assets/media/my-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals($folder->id, $file->folder_id);
    }

    /** @test */
    public function it_can_create_a_file_in_sub_sub_folder()
    {
        $folderRepository = app(FolderRepository::class);
        $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $nestedFolder = $folderRepository->create(['name' => 'Nested Folder', 'parent_id' => 1]);

        $file = $this->file->createFromFile(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $nestedFolder->id);

        $this->assertEquals('/assets/media/my-folder/nested-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals($nestedFolder->id, $file->folder_id);
    }

    /** @test */
    public function it_can_fetch_all_files_only()
    {
        $folderRepository = app(FolderRepository::class);
        $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $this->createFile();
        $this->createFile();

        $this->assertCount(2, $this->file->allForGrid());
    }

    /** @test */
    public function it_can_store_same_filename_in_other_folder_with_no_suffix()
    {
        $folderRepository = app(FolderRepository::class);
        $folder = $folderRepository->create(['name' => 'My Folder', 'parent_id' => 0]);
        $file = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $folder->id);
        $fileTwo = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'));

        $subFolder = $folderRepository->create(['name' => 'My Sub Folder', 'parent_id' => $folder->id]);
        $fileThree = app(FileService::class)->store(\Illuminate\Http\UploadedFile::fake()->image('my-file.jpg'), $subFolder->id);

        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-file.jpg')));
        $this->assertTrue($this->app['files']->exists(public_path('/assets/media/my-folder/my-sub-folder/my-file.jpg')));

        $this->assertEquals('/assets/media/my-folder/my-file.jpg', $file->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-file.jpg', $fileTwo->path->getRelativeUrl());
        $this->assertEquals('/assets/media/my-folder/my-sub-folder/my-file.jpg', $fileThree->path->getRelativeUrl());
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
