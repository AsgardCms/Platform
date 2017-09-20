<?php

namespace Modules\Media\Tests;

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
    }

    /** @test */
    public function it_can_create_a_folder()
    {
        $folder = $this->folder->create(['name' => 'My Folder']);

        $this->assertCount(1, $this->folder->all());
        $this->assertEquals('My Folder', $folder->filename);
        $this->assertEquals('my-folder', $folder->path->getRelativeUrl());
        $this->assertTrue( $folder->is_folder);
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
