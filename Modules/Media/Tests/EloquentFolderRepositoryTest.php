<?php

namespace Modules\Media\Tests;

use Illuminate\Support\Facades\Event;
use Modules\Media\Events\FolderIsCreating;
use Modules\Media\Events\FolderWasCreated;
use Modules\Media\Repositories\Eloquent\EloquentFolderRepository;
use Modules\Media\Repositories\FolderRepository;
use PHPUnit\Framework\TestCase;

final class EloquentFolderRepositoryTest extends MediaTestCase
{
    /**
     * @var FolderRepository
     */
    private $folder;

    public function setUp()
    {
        parent::setUp();

        $this->resetDatabase();

        $this->folder = app(FolderRepository::class);
        $this->app['config']->set('asgard.media.config.files-path', '/assets/media/');
    }

    /** @test */
    public function it_can_create_a_folder_in_database()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);

        $this->assertCount(1, $this->folder->all());
        $this->assertEquals('My Folder', $folder->filename);
        $this->assertEquals('/assets/media/my-folder', $folder->path->getRelativeUrl());
        $this->assertTrue( $folder->is_folder);
        $this->assertTrue( $folder->isFolder());
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
