<?php

namespace Modules\User\Tests\Permissions;

use Modules\User\Permissions\PermissionsRemover;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Tests\BaseUserTestCase;

class PermissionsRemoverTest extends BaseUserTestCase
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

        $this->role->create(['name' => 'Admin', 'slug' => 'admin', 'permissions' => ['dashboard.index' => true, 'user.users.index' => true, 'user.users.create' => true,]]);
        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
            'permissions' => ['dashboard.index' => true, 'user.users.index' => true, 'user.users.create' => true,],
        ]);
        $this->app->config->set('asgard.user.permissions', [
            'user.users' => [
                'index' => 'user::users.list user',
                'create' => 'user::users.create user',
            ],
        ]);
    }

    /** @test */
    public function it_removes_all_module_permissions_from_roles()
    {
        $role = $this->role->findByName('Admin');

        $this->assertCount(3, $role->permissions);

        (new PermissionsRemover('User'))->removeAll();

        $role->refresh();
        $this->assertCount(1, $role->permissions);
    }

    /** @test */
    public function it_removes_all_module_permissions_from_users()
    {
        $user = $this->user->find(1);

        $this->assertCount(3, $user->permissions);

        (new PermissionsRemover('User'))->removeAll();

        $user->refresh();
        $this->assertCount(1, $user->permissions);
    }
}
