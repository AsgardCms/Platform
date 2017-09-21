<?php

namespace Modules\Media\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Events\FolderWasCreated;
use Modules\Media\Repositories\FolderRepository;

class EloquentFolderRepository extends EloquentBaseRepository implements FolderRepository
{
    public function create($data)
    {
        $folder = $this->model->create([
            'filename' => array_get($data, 'name'),
            'path' => config('asgard.media.config.files-path') . str_slug(array_get($data, 'name')),
            'is_folder' => true,
            'folder_id' => array_get($data, 'parent_id'),
        ]);

        event(new FolderWasCreated($folder, $data));

        return $folder;
    }
}
