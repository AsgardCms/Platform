<?php namespace Modules\User\Listeners;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Laracasts\Commander\Events\EventListener;
use Modules\User\Repositories\AuthenticationRepository;

class SendRegistrationConfirmationEmail extends EventListener
{
    /**
     * @var AuthenticationRepository
     */
    private $auth;

    public function __construct(AuthenticationRepository $auth)
    {
        $this->auth = $auth;
    }

    public function whenUserHasRegistered($event)
    {
        $user = $event->user;

        $activation = $this->auth->createActivation($user);

        $data = [
            'user' => $user,
            'activationcode' => $activation->code
        ];

        Mail::queue('user::emails.welcome',$data,
            function (Message $m) use ($user) {
                $m->to($user->email)->subject('Welcome.');
            }
        );
    }
}
