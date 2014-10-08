<?php  namespace Modules\Core\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\CoreRepository;

abstract class EloquentCoreRepository implements CoreRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function add($data)
    {
        return $this->model->create($data);
    }

    public function remove($ids)
    {
        return $this->model->destroy($ids);
    }
}
