<?php

namespace Modules\User\Events;

class UserWasUpdated
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
