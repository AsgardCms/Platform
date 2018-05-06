<?php

namespace Modules\User\Tests;

use Illuminate\Support\Facades\Event;
use Modules\User\Entities\Sentinel\User;
use Modules\User\Events\UserHasRegistered;
use Modules\User\Events\UserIsCreating;
use Modules\User\Events\UserIsUpdating;
use Modules\User\Events\UserWasCreated;
use Modules\User\Events\UserWasUpdated;
use Modules\User\Exceptions\UserNotFoundException;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;

class SentinelUserRepositoryTest extends BaseUserTestCase
{
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var UserRepository
     */
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->role = app(RoleRepository::class);
        $this->user = app(UserRepository::class);
    }

    /** @test */
    public function it_creates_a_new_user()
    {
        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $user = $this->user->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertCount(1, $this->user->all());
    }

    /** @test */
    public function it_fires_event_when_user_is_creating()
    {
        Event::fake();

        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        Event::assertDispatched(UserIsCreating::class, function ($e) use ($user) {
            return $e->getAttribute('email') === $user->email;
        });
    }

    /** @test */
    public function it_can_change_data_when_it_is_creating_event()
    {
        Event::listen(UserIsCreating::class, function (UserIsCreating $event) {
            $event->setAttributes(['email' => 'john@doe.com']);
        });

        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->assertEquals('john@doe.com', $user->email);
    }

    /** @test */
    public function it_can_change_the_data_multiple_times()
    {
        Event::listen(UserIsCreating::class, function (UserIsCreating $event) {
            $event->setAttributes(['email' => 'john@doe.com']);
        });
        Event::listen(UserIsCreating::class, function (UserIsCreating $event) {
            $event->setAttributes(['email' => 'jane@doe.com']);
        });

        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->assertEquals('jane@doe.com', $user->email);
    }

    /** @test */
    public function it_makes_sure_the_event_contains_original_attributes()
    {
        Event::fake();

        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        Event::assertDispatched(UserIsCreating::class, function ($e) {
            return $e->getOriginal('email')=== 'n.widart@gmail.com';
        });
    }

    /** @test */
    public function it_fires_event_when_user_created()
    {
        Event::fake();

        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        Event::assertDispatched(UserHasRegistered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /** @test */
    public function it_fires_event_when_user_has_registered()
    {
        Event::fake();

        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ], true);

        Event::assertDispatched(UserWasCreated::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /** @test */
    public function it_hashes_user_password()
    {
        $this->createRole('User');

        $userOne = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);
        $userTwo = $this->user->createWithRoles([
            'email' => 'jane@doe.com',
            'password' => 'demo1234',
        ], ['User']);
        $userThree = $this->user->createWithRolesFromCli([
            'email' => 'john@doe.com',
            'password' => 'demo1234',
        ], ['User']);

        $hasher = app('sentinel.hasher');

        $this->assertTrue($hasher->check('demo1234', $userOne->password));
        $this->assertNotEquals('demo1234', $userOne->password);

        $this->assertTrue($hasher->check('demo1234', $userTwo->password));
        $this->assertNotEquals('demo1234', $userTwo->password);

        $this->assertTrue($hasher->check('demo1234', $userThree->password));
        $this->assertNotEquals('demo1234', $userThree->password);
    }

    /** @test */
    public function it_creates_user_with_given_role()
    {
        $this->createRole('User');

        $user = $this->user->createWithRoles([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ], ['User']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertCount(1, $this->user->all());
    }

    /** @test */
    public function it_creates_a_user_token_when_creating_user_with_roles()
    {
        $this->createRole('User');

        $user = $this->user->createWithRoles([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ], ['User']);

        $this->assertCount(1, $user->api_keys);
    }

    /** @test */
    public function it_creates_user_without_triggering_events_for_cli()
    {
        Event::fake();

        $this->user->createWithRolesFromCli([
            'email' => 'john@doe.com',
            'password' => 'demo1234',
        ], ['User']);

        Event::assertNotDispatched(UserWasCreated::class);
        Event::assertNotDispatched(UserHasRegistered::class);
    }

    /** @test */
    public function it_creates_new_user_with_api_keys()
    {
        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->assertCount(1, $user->api_keys);
    }

    /** @test */
    public function it_updates_a_user()
    {
        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->user->update($user, ['first_name' => 'John', 'last_name' => 'Doe']);

        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
    }

    /** @test */
    public function it_triggers_events_on_user_update()
    {
        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        Event::fake();

        $this->user->update($user, ['first_name' => 'John', 'last_name' => 'Doe']);

        Event::assertDispatched(UserWasUpdated::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
        Event::assertDispatched(UserIsUpdating::class, function ($e) use ($user) {
            return $e->getUser()->id === $user->id;
        });
    }

    /** @test */
    public function it_triggers_event_when_user_is_updating()
    {
        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        Event::fake();

        $this->user->update($user, ['first_name' => 'John', 'last_name' => 'Doe']);

        Event::assertDispatched(UserIsUpdating::class, function ($e) use ($user) {
            return $e->getUser()->id === $user->id &&
                    $e->getAttribute('first_name') === 'John';
        });
    }

    /** @test */
    public function it_can_change_properties_before_update()
    {
        Event::listen(UserIsUpdating::class, function (UserIsUpdating $event) {
            $event->setAttributes(['first_name' => 'Jane']);
        });

        $user = $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->user->update($user, ['first_name' => 'John', 'last_name' => 'Doe']);

        $this->assertEquals('Jane', $this->user->find(1)->first_name);
    }

    /** @test */
    public function it_updates_user_and_syncs_roles()
    {
        $this->createRole('User');
        $this->createRole('Admin');
        $user = $this->user->createWithRoles([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ], [1]);

        $this->user->updateAndSyncRoles($user->id, ['first_name' => 'John', 'last_name' => 'Doe', 'activated' => 1], [2]);

        $user->refresh();

        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertCount(1, $user->roles);
    }

    /** @test */
    public function it_triggers_event_on_user_update_and_role_sync()
    {
        $this->createRole('User');
        $this->createRole('Admin');
        $user = $this->user->createWithRoles([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ], [1]);
        Event::fake();

        $this->user->updateAndSyncRoles($user->id, ['first_name' => 'John', 'last_name' => 'Doe', 'activated' => 1], [2]);

        Event::assertDispatched(UserWasUpdated::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
        Event::assertDispatched(UserIsUpdating::class, function ($e) use ($user) {
            return $e->getUser()->id === $user->id;
        });
    }

    /** @test */
    public function it_can_change_properties_before_update_and_sync_roles()
    {
        Event::listen(UserIsUpdating::class, function (UserIsUpdating $event) {
            $event->setAttributes(['first_name' => 'Jane']);
        });

        $this->createRole('Admin');
        $user = $this->user->createWithRoles([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ], [1]);

        $this->user->updateAndSyncRoles($user->id, ['first_name' => 'John', 'last_name' => 'Doe', 'activated' => 1], [1]);

        $this->assertEquals('Jane', $this->user->find(1)->first_name);
    }

    /** @test */
    public function it_deletes_a_user()
    {
        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->assertCount(1, $this->user->all());
        $this->user->delete(1);
        $this->assertCount(0, $this->user->all());
    }

    /** @test */
    public function it_throws_exception_if_user_not_found_when_deleting()
    {
        $this->expectException(UserNotFoundException::class);

        $this->user->delete(1);
    }

    /** @test */
    public function it_finds_a_user_by_its_credentials()
    {
        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $user = $this->user->findByCredentials([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $this->assertEquals('n.widart@gmail.com', $user->email);
    }

    private function createRole($name)
    {
        return $this->role->create([
            'name' => $name,
            'slug' => str_slug($name),
        ]);
    }
}
