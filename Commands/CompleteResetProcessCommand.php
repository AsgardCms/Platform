<?php namespace Modules\User\Commands;

class CompleteResetProcessCommand
{
    public $password_confirmation;
    public $password;
    public $userId;
    public $code;

    public function __construct($password, $password_confirmation, $userId, $code)
    {
        $this->password_confirmation = $password_confirmation;
        $this->password = $password;
        $this->userId = $userId;
        $this->code = $code;
    }
}
