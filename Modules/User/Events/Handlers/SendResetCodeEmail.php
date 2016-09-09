<?php

namespace Modules\User\Events\Handlers;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Modules\User\Events\UserHasBegunResetProcess;

class SendResetCodeEmail
{
    public function handle(UserHasBegunResetProcess $event)
    {
        $user = $event->user;
        $code = $event->code;

        Mail::queue('user::emails.reminder', compact('user', 'code'), function (Message $m) use ($user) {
            $m->to($user->email)->subject('Reset your account password.');
        });
    }
}
