<?php namespace Modules\Core\Repositories;

/**
 * Interface CoreRepository
 * @package Modules\Core\Repositories
 */
interface BaseRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * Update a resource
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data);

    /**
     * @param $ids
     * @return mixed
     */
    public function destroy($ids);
}
