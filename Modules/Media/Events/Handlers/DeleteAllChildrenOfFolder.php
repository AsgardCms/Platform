<?php

namespace Modules\Media\Events\Handlers;

use Modules\Media\Events\FolderIsDeleting;
use Modules\Media\Repositories\FolderRepository;

class DeleteAllChildrenOfFolder
{
    /**
     * @var FolderRepository
     */
    private $folder;

    public function __construct(FolderRepository $folder)
    {
        $this->folder = $folder;
    }

    public function handle(FolderIsDeleting $event)
    {
        $children = $this->folder->allChildrenOf($event->folder);
        foreach ($children as $child) {
            $child->delete();
        }
    }
}
