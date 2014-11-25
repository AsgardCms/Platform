<?php namespace Modules\User\Repositories\Sentry;

use Modules\User\Repositories\RoleRepository;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

class SentryRoleRepository implements RoleRepository
{
    /**
     * Return all the roles
     * @return mixed
     */
    public function all()
    {
        return Sentry::findAllGroups();
    }

    /**
     * Create a role resource
     * @return mixed
     */
    public function create($data)
    {
        Sentry::createGroup($data);
    }

    /**
     * Find a role by its id
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Sentry::findGroupById($id);
    }

    /**
     * Update a role
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $role = Sentry::findGroupById($id);

        $role->permissions($data);

        $role->save();
    }

    /**
     * Delete a role
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $role = Sentry::findGroupById($id);

        return $role->delete();
    }

    /**
     * Find a role by its name
     * @param string $name
     * @return mixed
     */
    public function findByName($name)
    {
        return Sentry::findGroupByName($name);
    }
}
