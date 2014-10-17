<?php namespace Modules\User\Commands;

use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Commander\CommandHandler;
use Modules\User\Exceptions\UserNotFoundException;

class CompleteResetProcessCommandHandler implements CommandHandler
{
    protected $input;

    /**
     * Handle the command
     *
     * @param $command
     * @throws InvalidOrExpiredResetCode
     * @throws UserNotFoundException
     * @return mixed
     */
    public function handle($command)
    {
        $this->input = $command;

        $this->form->validate((array) $this->input);

        $user = $this->findUser();

        if (!Reminder::complete($user, $this->input->code, $this->input->password)) {
            throw new InvalidOrExpiredResetCode;
        }

        return $user;
    }

    public function findUser()
    {
        $user = Sentinel::findById($this->input->userId);
        if ($user) {
            return $user;
        }

        throw new UserNotFoundException;
    }
}
