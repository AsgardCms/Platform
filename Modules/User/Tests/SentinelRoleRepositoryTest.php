<?php

namespace Modules\User\Tests;

use Illuminate\Support\Facades\Event;
use Modules\User\Events\RoleIsCreating;
use Modules\User\Events\RoleIsUpdating;
use Modules\User\Events\RoleWasCreated;
use Modules\User\Events\RoleWasUpdated;
use Modules\User\Repositories\RoleRepository;

class SentinelRoleRepositoryTest extends BaseUserTestCase
{
    /**
     * @var RoleRepository
     */
    private $role;

    public function setUp(): void
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
    public function it_triggers_event_when_role_was_created()
    {
        Event::fake();

        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        Event::assertDispatched(RoleWasCreated::class, function ($e) use ($role) {
            return $e->role->name === $role->name;
        });
    }

    /** @test */
    public function it_triggers_event_when_role_is_creating()
    {
        Event::fake();

        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        Event::assertDispatched(RoleIsCreating::class, function ($e) use ($role) {
            return $e->getAttribute('name') === $role->name;
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(RoleIsCreating::class, function (RoleIsCreating $event) {
            $event->setAttributes(['name' => 'BETTER']);
        });

        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);

        $this->assertEquals('BETTER', $role->name);
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
    public function it_fires_event_when_role_is_updating()
    {
        Event::fake();

        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);
        $this->role->update($role->id, ['name' => 'Better Admin']);

        Event::assertDispatched(RoleIsUpdating::class, function ($e) use ($role) {
            return $e->getRole()->id === $role->id;
        });
    }

    /** @test */
    public function it_can_change_data_before_role_is_updated()
    {
        Event::listen(RoleIsUpdating::class, function (RoleIsUpdating $event) {
            $event->setAttributes(['name' => 'BETTER']);
        });

        $role = $this->role->create(['name' => 'Admin', 'slug' => 'admin']);
        $role = $this->role->update($role->id, ['name' => 'Better Admin']);

        $this->assertEquals('BETTER', $role->name);
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
