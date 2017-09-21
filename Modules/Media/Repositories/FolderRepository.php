<?php

namespace Modules\Media\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Media\Entities\File;

interface FolderRepository extends BaseRepository
{
    /**
     * Find a folder by its ID
     * @param int $folderId
     * @return File
     */
    public function findFolder(int $folderId) : File;
}
