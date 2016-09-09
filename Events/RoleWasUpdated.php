<?php

namespace Modules\User\Events;

class RoleWasUpdated
{
    public $role;

    public function __construct($role)
    {
        $this->role = $role;
    }
}
