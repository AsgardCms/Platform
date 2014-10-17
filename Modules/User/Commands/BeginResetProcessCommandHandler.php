<?php namespace Modules\User\Commands;

use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Event;
use Laracasts\Commander\CommandHandler;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Exceptions\UserNotFoundException;

class BeginResetProcessCommandHandler implements CommandHandler
{
    /**
     * Handle the command
     *
     * @param $command
     * @throws UserNotFoundException
     * @return mixed
     */
    public function handle($command)
    {
        $user = $this->findUser((array) $command);

        $reminder = Reminder::exists($user) ?: Reminder::create($user);

        Event::fire('Modules.Session.Events.UserHasBegunResetProcess', new UserHasBegunResetProcess($user, $reminder));
    }

    private function findUser($credentials)
    {
        $user = Sentinel::findByCredentials((array) $credentials);
        if ($user) {
            return $user;
        }

        throw new UserNotFoundException();
    }
}
