<?php

namespace Modules\Media\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;
use Modules\Media\Entities\File;

interface FolderRepository extends BaseRepository
{
    /**
     * Find a folder by its ID
     * @param int $folderId
     * @return File|null
     */
    public function findFolder(int $folderId);

    /**
     * @param File $folder
     * @return Collection
     */
    public function allChildrenOf(File $folder);
}
