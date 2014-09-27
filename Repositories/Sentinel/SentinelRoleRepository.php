<?php namespace Modules\User\Repositories\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\User\Repositories\RoleRepository;

class SentinelRoleRepository implements RoleRepository
{
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $role;

    public function __construct()
    {
        $this->role = Sentinel::getRoleRepository()->createModel();
    }

    /**
     * Return all the roles
     * @return mixed
     */
    public function all()
    {
        return $this->role->all();
    }

    /**
     * Create a role resource
     * @return mixed
     */
    public function create($data)
    {
        // TODO: Implement create() method.
    }
}