<?php

namespace Modules\User\Tests;

use Modules\User\Entities\Sentinel\User;
use Modules\User\Http\Controllers\Api\UserController;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\UserRepository;
use Modules\User\Repositories\UserTokenRepository;

class ApiUserControllerTest extends BaseUserTestCase
{
    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var PermissionManager
     */
    private $permissions;
    /**
     * @var UserTokenRepository
     */
    private $userToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = app(UserRepository::class);
        $this->permissions = app(PermissionManager::class);
        $this->userToken = app(UserTokenRepository::class);
    }

    /** @test */
    public function it_creates_a_new_activated_user()
    {
        $data = [
            'email' => 'user@domain.tld',
            'password' => 'Pa$$w0rd',
            'is_activated' => true,
        ];

        $request = CreateUserRequest::create('', '', $data);
        $controller = new UserController($this->user, $this->permissions, $this->userToken);

        $controller->store($request);
        $user = $this->user->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->isActivated());
    }
}
