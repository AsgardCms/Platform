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
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EloquentFileRepositoryTest extends MediaTestCase
{
    /**
     * @var FileRepository
     */
    private $file;

    public function setUp()
    {
        parent::setUp();

        $this->resetDatabase();

        $this->file = app(FileRepository::class);
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

        $file = $this->file->find(2);

        $this->assertEquals('my-file_1.jpg', $file->filename);
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

    private function resetDatabase()
    {
        // Makes sure the migrations table is created
        $this->artisan('migrate', [
            '--database' => 'sqlite',
        ]);
        // We empty all tables
        $this->artisan('migrate:reset', [
            '--database' => 'sqlite',
        ]);
        // Migrate
        $this->artisan('migrate', [
            '--database' => 'sqlite',
        ]);

        $this->artisan('migrate', [
            '--database' => 'sqlite',
            '--path'     => 'Modules/Tag/Database/Migrations',
        ]);
    }
}
