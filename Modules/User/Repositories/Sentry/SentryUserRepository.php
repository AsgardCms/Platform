<?php namespace Modules\User\Repositories\Sentry;

use Modules\User\Repositories\UserRepository;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

class SentryUserRepository implements UserRepository
{
    /**
     * Returns all the users
     * @return object
     */
    public function all()
    {
        return Sentry::findAllUsers();
    }

    /**
     * Create a user resource
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Sentry::createUser($data);
    }

    /**
     * Create a user and assign roles to it
     * @param array $data
     * @param array $roles
     * @param bool $activated
     */
    public function createWithRoles($data, $roles, $activated = false)
    {
        $user = Sentry::createUser(array_merge($data, ['activated' => $activated]));
        if (!empty($roles)) {
            foreach ($roles as $roleId) {
                $group = Sentry::findGroupById($roleId);
                $user->addGroup($group);
            }
        }
    }

    /**
     * Find a user by its ID
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Sentry::findUserById($id);
    }

    /**
     * Update a user
     * @param $user
     * @param $data
     * @return mixed
     */
    public function update($user, $data)
    {
        $user = $user->update($data);

        return $user->save();
    }

    /**
     * Update a user and sync its roles
     * @param int $userId
     * @param $data
     * @param $roles
     * @return mixed
     */
    public function updateAndSyncRoles($userId, $data, $roles)
    {
        $user = Sentry::findUserById($userId);
        $user = $user->update($data);
        $user->save();
        if (!empty($roles)) {
            $adminGroup = Sentry::findGroupByName($roles);
            $user->removeGroup();
            $user->addGroup($adminGroup);
        }
    }

    /**
     * Deletes a user
     * @param $id
     * @return mixed
     * @throws UserNotFoundException
     */
    public function delete($id)
    {
        if ($user = Sentry::findUserById($id)) {
            return $user->delete();
        };
        throw new UserNotFoundException;
    }

    /**
     * Find a user by its credentials
     * @param array $credentials
     * @return mixed
     */
    public function findByCredentials(array $credentials)
    {
        return Sentry::findUserByCredentials($credentials);
    }
}
