<?php namespace Modules\User\Commands;

use Illuminate\Support\Facades\Event;
use Laracasts\Commander\CommandHandler;
use Modules\User\Events\UserHasRegistered;
use Modules\User\Repositories\AuthenticationRepository;
use Modules\User\Repositories\RoleRepository;

class RegisterNewUserCommandHandler implements CommandHandler
{
    protected $input;

    /**
     * @var AuthenticationRepository
     */
    private $auth;
    /**
     * @var RoleRepository
     */
    private $role;

    public function __construct(AuthenticationRepository $auth, RoleRepository $role)
    {
        $this->auth = $auth;
        $this->role = $role;
    }

    /**
     * Handle the command
     *
     * @param $input
     * @return mixed
     */
    public function handle($input)
    {
        $this->input = $input;

        $user = $this->createUser();

        $this->assignUserToUsersGroup($user);

        Event::fire('Modules.User.Events.UserHasRegistered', new UserHasRegistered($user));

        return $user;
    }

    private function createUser()
    {
        return $this->auth->register((array) $this->input);
    }

    private function assignUserToUsersGroup($user)
    {
        $role = $this->role->findByName('User');

        $this->auth->assignRole($user, $role);
    }
}
