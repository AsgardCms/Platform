<?php namespace Modules\User\Commands;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Event;
use Laracasts\Commander\CommandHandler;
use Modules\User\Events\UserHasRegistered;

class RegisterNewUserCommandHandler implements CommandHandler
{
    protected $input;

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

        Event::fire('Modules.Session.Events.UserHasRegistered', new UserHasRegistered($user));

        return $user;
    }

    private function createUser()
    {
        return Sentinel::getUserRepository()->create((array) $this->input);
    }

    private function assignUserToUsersGroup($user)
    {
        $group = Sentinel::findRoleByName('User');

        $group->users()->attach($user);
    }
}
