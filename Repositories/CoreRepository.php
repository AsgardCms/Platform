<?php namespace Modules\Core\Repositories;

interface CoreRepository
{
    public function find($id);

    public function all();

    public function add($data);

    public function remove($ids);

}
