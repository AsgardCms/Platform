<?php

namespace Modules\User\Events;

class UserWasCreated
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
