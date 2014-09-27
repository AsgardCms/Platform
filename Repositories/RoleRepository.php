<?php namespace Modules\User\Repositories;

interface RoleRepository
{
    /**
     * Return all the roles
     * @return mixed
     */
    public function all();

    /**
     * Create a role resource
     * @return mixed
     */
    public function create($data);
}