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

    /**
     * Return all categories in the given language
     * @param $lang
     * @return mixed
     */
    public function allTranslatedIn($lang)
    {
        return $this->model->whereHas('translations', function($q) use($lang)
        {
            $q->where('locale', "$lang");
        })->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Find a resource by the given slug
     * @param int $slug
     * @return object
     */
    public function findBySlug($slug)
    {
        return $this->model->whereHas('translations', function($q) use($slug)
        {
            $q->where('slug', "$slug");
        })->first();
    }
}
