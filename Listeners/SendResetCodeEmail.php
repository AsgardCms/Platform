<?php namespace Modules\User\Listeners;

use Illuminate\Support\Facades\Mail;
use Laracasts\Commander\Events\EventListener;

class SendResetCodeEmail extends EventListener
{
    public function whenUserHasBegunResetProcess($event)
    {
        $user = $event->user;
        $code = $event->reminder->code;

        Mail::queue('SessionModule::emails.reminder', compact('user', 'code'), function($m) use ($user)
        {
            $m->to($user->email)->subject('Reset your account password.');
        });
    }
}