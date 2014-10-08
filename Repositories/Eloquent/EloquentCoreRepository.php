<?php  namespace Modules\Core\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\CoreRepository;

/**
 * Class EloquentCoreRepository
 * @package Modules\Core\Repositories\Eloquent
 */
abstract class EloquentCoreRepository implements CoreRepository
{
    /**
     * @var Model An instance of the Eloquent Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param int|int[] $ids
     * @return mixed
     */
    public function remove($ids)
    {
        return $this->model->destroy($ids);
    }
}
