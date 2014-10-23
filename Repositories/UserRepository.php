<?php namespace Modules\User\Repositories;

/**
 * Interface UserRepository
 * @package Modules\User\Repositories
 */
interface UserRepository
{
    /**
     * Returns all the users
     * @return object
     */
    public function all();

    /**
     * Create a user resource
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Create a user and assign roles to it
     * @param array $data
     * @param array $roles
     * @return void
     */
    public function createWithRoles($data, $roles);

    /**
     * Find a user by its ID
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Update a user
     * @param $user
     * @param $data
     * @return mixed
     */
    public function update($user, $data);

    /**
     * Update a user and sync its roles
     * @param $user
     * @param $data
     * @param $roles
     * @return mixed
     */
    public function updateAndSyncRoles($user, $data, $roles);

    /**
     * Deletes a user
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Find a user by its credentials
     * @param array $credentials
     * @return mixed
     */
    public function findByCredentials(array $credentials);
}
