<?php

namespace Modules\User\Tests\Permissions;

use Modules\User\Permissions\PermissionsAdder;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Tests\BaseUserTestCase;

final class PermissionsAdderTest extends BaseUserTestCase
{
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var UserRepository
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = app(RoleRepository::class);
        $this->user = app(UserRepository::class);

        $this->role->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'dashboard.index' => true,
            ],
        ]);
        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
            'permissions' => ['dashboard.index' => true,],
        ]);
        $this->app->config->set('asgard.user.permissions', [
            'user.users' => [
                'index' => 'user::users.list user',
                'create' => 'user::users.create user',
            ],
        ]);
    }

    /** @test */
    public function it_can_add_permissions_to_the_admin_role()
    {
        $role = $this->role->findByName('Admin');

        $this->assertCount(1, $role->permissions);

        (new PermissionsAdder('User'))->addAll();

        $role->refresh();
        $this->assertCount(3, $role->permissions);
    }
}
