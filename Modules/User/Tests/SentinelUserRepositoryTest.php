<?php

namespace Modules\User\Tests;

use Modules\User\Entities\UserToken;
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
    public function it_creates_new_user_with_api_keys()
    {
        $this->user->create([
            'email' => 'n.widart@gmail.com',
            'password' => 'demo1234',
        ]);
        $user = $this->user->find(1);

        $this->assertCount(1, $user->api_keys);
    }

    private function createRole($name)
    {
        return $this->role->create([
            'name' => $name,
            'slug' => str_slug($name),
        ]);
    }
}
