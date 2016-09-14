<?php

namespace Modules\User\Events\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\ResetPasswordEmail;
use Modules\User\Events\UserHasBegunResetProcess;

class SendResetCodeEmail
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(UserHasBegunResetProcess $event)
    {
        $this->mailer->to($event->user->email)->send(new ResetPasswordEmail($event->user, $event->code));
    }
}
