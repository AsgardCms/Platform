<?php  namespace Modules\Core\Repositories\Eloquent;

use Modules\Core\Repositories\BaseRepository;

/**
 * Class EloquentCoreRepository
 * @package Modules\Core\Repositories\Eloquent
 */
abstract class EloquentBaseRepository implements BaseRepository
{
    /**
     * @var Model An instance of the Eloquent Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct($model)
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
        return $this->model->orderBy('created_at', 'DESC')->get();
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($model, $data)
    {
        return $model->update($data);
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function destroy($model)
    {
        return $model->delete();
    }
}
