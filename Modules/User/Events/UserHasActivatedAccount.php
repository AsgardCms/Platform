<?php

namespace Modules\User\Events;

class UserHasActivatedAccount
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
