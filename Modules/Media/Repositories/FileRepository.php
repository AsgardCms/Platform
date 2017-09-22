<?php

namespace Modules\Media\Repositories;

use Illuminate\Http\Request;
use Modules\Core\Repositories\BaseRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileRepository extends BaseRepository
{
    /**
     * Create a file row from the given file
     * @param  UploadedFile $file
     * @param int $parentId
     * @return mixed
     */
    public function createFromFile(UploadedFile $file, int $parentId = 0);

    /**
     * Find a file for the entity by zone
     * @param string $zone
     * @param object $entity
     * @return object
     */
    public function findFileByZoneForEntity($zone, $entity);

    /**
     * Find multiple files for the given zone and entity
     * @param string $zone
     * @param object $entity
     * @return object
     */
    public function findMultipleFilesByZoneForEntity($zone, $entity);

    /**
     * @param Request $request
     * @return mixed
     */
    public function serverPaginationFilteringFor(Request $request);
}
