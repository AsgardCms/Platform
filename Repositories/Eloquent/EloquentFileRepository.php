<?php namespace Modules\Media\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Repositories\FileRepository;

class EloquentFileRepository extends EloquentBaseRepository implements FileRepository
{
    /**
     * Update a resource
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
    }
}
