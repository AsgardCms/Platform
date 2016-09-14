<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Entities\UserInterface;

class ResetPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var UserInterface
     */
    public $user;
    /**
     * @var
     */
    public $code;
    protected $subject = 'Reset your account password.';

    public function __construct(UserInterface $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::emails.reminder');
    }
}
