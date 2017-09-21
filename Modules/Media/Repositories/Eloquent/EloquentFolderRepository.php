<?php

namespace Modules\Media\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Entities\File;
use Modules\Media\Events\FolderIsCreating;
use Modules\Media\Events\FolderWasCreated;
use Modules\Media\Repositories\FolderRepository;

class EloquentFolderRepository extends EloquentBaseRepository implements FolderRepository
{
    /**
     * Find a folder by its ID
     * @param int $folderId
     * @return File
     */
    public function findFolder(int $folderId): File
    {
        return $this->model->where('is_folder', 1)->where('id', $folderId)->first();
    }

    public function create($data)
    {
        $data = [
            'filename' => array_get($data, 'name'),
            'path' => $this->getPath($data),
            'is_folder' => true,
            'folder_id' => array_get($data, 'parent_id'),
        ];
        event($event = new FolderIsCreating($data));
        $folder = $this->model->create($event->getAttributes());

        event(new FolderWasCreated($folder, $data));

        return $folder;
    }

    /**
     * @param array $data
     * @return string
     */
    private function getPath(array $data): string
    {
        if (array_key_exists('parent_id', $data)) {
            $parent = $this->findFolder($data['parent_id']);

            return $parent->path->getRelativeUrl() . '/' . str_slug(array_get($data, 'name'));
        }

        return config('asgard.media.config.files-path') . str_slug(array_get($data, 'name'));
    }
}
