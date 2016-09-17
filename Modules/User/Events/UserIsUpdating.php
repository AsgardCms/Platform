<?php

namespace Modules\User\Events;

class UserIsUpdating
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
