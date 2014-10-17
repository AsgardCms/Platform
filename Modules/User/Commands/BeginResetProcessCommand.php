<?php namespace Modules\User\Commands;

class BeginResetProcessCommand
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
}
