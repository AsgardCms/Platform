<?php namespace Modules\User\Listeners;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Laracasts\Commander\Events\EventListener;

class SendResetCodeEmail extends EventListener
{
    public function whenUserHasBegunResetProcess($event)
    {
        $user = $event->user;
        $code = $event->code;

        Mail::queue('user::emails.reminder', compact('user', 'code'), function(Message $m) use ($user)
        {
            $m->to($user->email)->subject('Reset your account password.');
        });
    }
}
