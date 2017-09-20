<?php

namespace Modules\Media\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Repositories\FolderRepository;

class EloquentFolderRepository extends EloquentBaseRepository implements FolderRepository
{
    public function create($data)
    {
        return $this->model->create([
            'filename' => array_get($data, 'name'),
            'path' => str_slug(array_get($data, 'name')),
            'is_folder' => true,
        ]);
    }
}
