<?php

namespace Modules\User\Tests;

use Illuminate\Support\Facades\Event;
use Modules\User\Events\RoleWasUpdated;
use Modules\User\Repositories\RoleRepository;

class SentinelRoleRepositoryTest extends BaseUserTestCase
{
    /**
     * @var RoleRepository
     */
    private $role;

    public function setUp()
    {
        parent::setUp();
        $this->role = app(RoleRepository::class);
    }

    /** @test */
    public function it_creates_a_role()
    {
        $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        $this->assertCount(1, $this->role->all());
    }

    /** @test */
    public function it_finds_a_role_by_id()
    {
        $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        $role = $this->role->find(1);

        $this->assertEquals('Admin', $role->name);
    }

    /** @test */
    public function it_updates_a_role()
    {
        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        $this->role->update($role->id, ['name' => 'Better Admin']);

        $role->refresh();

        $this->assertEquals('Better Admin', $role->name);
    }

    /** @test */
    public function it_triggers_role_updated_event()
    {
        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        Event::fake();

        $this->role->update($role->id, ['name' => 'Better Admin']);

        Event::assertDispatched(RoleWasUpdated::class, function ($e) use ($role) {
            return $e->role->id === $role->id;
        });
    }

    /** @test */
    public function it_deletes_a_role()
    {
        $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        $this->assertCount(1, $this->role->all());
        $this->role->delete(1);
        $this->assertCount(0, $this->role->all());
    }

    /** @test */
    public function it_finds_a_role_by_its_name()
    {
        $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        $role = $this->role->findByName('Admin');

        $this->assertEquals('Admin', $role->name);
    }
}
