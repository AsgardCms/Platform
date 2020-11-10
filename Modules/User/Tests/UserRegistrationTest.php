<?php

namespace Modules\User\Tests;

use Illuminate\Support\Str;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserRegistration;

class UserRegistrationTest extends BaseUserTestCase
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
    }

    /** @test */
    public function it_registers_a_new_user_with_user_role()
    {
        $this->createRole('User');

        app(UserRegistration::class)->register([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);

        $user = $this->user->find(1);

        self::assertCount(1, $this->user->all());
        self::assertEquals('n.widart@gmail.com', $user->email);
        self::assertEquals('User', $user->roles->first()->name);
    }

    private function createRole($name)
    {
        return $this->role->create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }
}
