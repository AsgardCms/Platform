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
    public function add($data);

    /**
     * @param $ids
     * @return mixed
     */
    public function remove($ids);

}
