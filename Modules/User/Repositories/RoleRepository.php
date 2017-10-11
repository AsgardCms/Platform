<?php

namespace Modules\User\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Interface RoleRepository
 * @package Modules\User\Repositories
 */
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

    /**
     * Find a role by its id
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Update a role
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data);

    /**
     * Delete a role
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Find a role by its name
     * @param  string $name
     * @return mixed
     */
    public function findByName($name);

    /**
     * Paginating, ordering and searching through pages for server side index table
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringFor(Request $request) : LengthAwarePaginator;
}
