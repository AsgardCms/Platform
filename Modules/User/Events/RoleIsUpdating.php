<?php

namespace Modules\User\Events;

use Cartalyst\Sentinel\Roles\RoleInterface;
use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class RoleIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var RoleInterface
     */
    private $role;

    public function __construct(RoleInterface $role, $attributes)
    {
        $this->role = $role;
        parent::__construct($attributes);
    }

    /**
     * @return RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }
}
