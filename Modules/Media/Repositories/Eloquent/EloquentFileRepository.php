<?php

namespace Modules\Media\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Entities\File;
use Modules\Media\Events\FileIsCreating;
use Modules\Media\Events\FileIsUpdating;
use Modules\Media\Events\FileWasCreated;
use Modules\Media\Events\FileWasUpdated;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EloquentFileRepository extends EloquentBaseRepository implements FileRepository
{
    /**
     * Update a resource
     * @param  File  $file
     * @param $data
     * @return mixed
     */
    public function update($file, $data)
    {
        event($event = new FileIsUpdating($file, $data));
        $file->update($event->getAttributes());

        $file->setTags(array_get($data, 'tags', []));

        event(new FileWasUpdated($file));

        return $file;
    }

    /**
     * Create a file row from the given file
     * @param  UploadedFile $file
     * @param int $parentId
     * @return mixed
     */
    public function createFromFile(UploadedFile $file, int $parentId = 0)
    {
        $fileName = FileHelper::slug($file->getClientOriginalName());

        $exists = $this->model->whereFilename($fileName)->first();

        if ($exists) {
            $fileName = $this->getNewUniqueFilename($fileName);
        }

        $data = [
            'filename' => $fileName,
            'path' => $this->getPathFor($fileName, $parentId),
            'extension' => substr(strrchr($fileName, '.'), 1),
            'mimetype' => $file->getClientMimeType(),
            'filesize' => $file->getFileInfo()->getSize(),
            'folder_id' => 0,
        ];

        event($event = new FileIsCreating($data));

        $file = $this->model->create($event->getAttributes());
        event(new FileWasCreated($file));

        return $file;
    }

    private function getPathFor(string $filename, int $folderId)
    {
        if ($folderId !== 0) {
            $parent = app(FolderRepository::class)->findFolder($folderId);
            if ($parent !== null) {
                return $parent->path->getRelativeUrl() . '/' . $filename;
            }
        }

        return config('asgard.media.config.files-path') . $filename;
    }

    public function destroy($file)
    {
        $file->delete();
    }

    /**
     * Find a file for the entity by zone
     * @param $zone
     * @param object $entity
     * @return object
     */
    public function findFileByZoneForEntity($zone, $entity)
    {
        foreach ($entity->files as $file) {
            if ($file->pivot->zone == $zone) {
                return $file;
            }
        }

        return '';
    }

    /**
     * Find multiple files for the given zone and entity
     * @param zone $zone
     * @param object $entity
     * @return object
     */
    public function findMultipleFilesByZoneForEntity($zone, $entity)
    {
        $files = [];
        foreach ($entity->files as $file) {
            if ($file->pivot->zone == $zone) {
                $files[] = $file;
            }
        }

        return new Collection($files);
    }

    /**
     * @param $fileName
     * @return string
     */
    private function getNewUniqueFilename($fileName)
    {
        $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
        $model = $this->model->where('filename', 'LIKE', "$fileNameOnly%")->orderBy('created_at', 'desc')->first();
        $latestFilename = pathinfo($model->filename, PATHINFO_FILENAME);
        $extension = pathinfo($model->filename, PATHINFO_EXTENSION);

        $version = substr($latestFilename, -1, strpos($latestFilename, '_'));
        $version++;

        return $fileNameOnly . '_' . $version . '.' . $extension;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function serverPaginationFilteringFor(Request $request)
    {
        $media = $this->allWithBuilder();

        $media->orderBy('is_folder', 'desc');
        $media->where('folder_id', $request->get('folder_id'));

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $media->where('filename', 'LIKE', "%{$term}%");
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $media->orderBy($request->get('order_by'), $order);
        } else {
            $media->orderBy('created_at', 'desc');
        }

        return $media->paginate($request->get('per_page', 10));
    }
}
