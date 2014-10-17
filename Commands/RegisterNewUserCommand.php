<?php namespace Modules\User\Commands;

class RegisterNewUserCommand
{
    public $email;
    public $password;
    public $password_confirmation;

    public function __construct($email, $password, $password_confirmation)
    {
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
    }
}
