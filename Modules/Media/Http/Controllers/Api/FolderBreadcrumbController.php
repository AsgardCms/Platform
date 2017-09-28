<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Media\Entities\File;
use Modules\Media\Repositories\FolderRepository;

class FolderBreadcrumbController extends Controller
{
    /**
     * @var FolderRepository
     */
    private $folder;
    private $breadcrumb = [
        //0 => 'Home',
    ];

    public function __construct(FolderRepository $folder)
    {
        $this->folder = $folder;
    }

    public function __invoke(File $folder)
    {
        if ($folder->folder_id !== 0) {
            $this->breadcrumb[] = ['id' => $folder->id, 'name' => $folder->filename];
        }

        $this->makeBreadcrumb($folder);

        $this->breadcrumb[] = ['id' => 0, 'name' => 'Home'];

        return response()->json(array_reverse($this->breadcrumb));
    }

    private function makeBreadcrumb($folder)
    {
        if ($folder->parent_folder === null) {
            return;
        }

        $this->breadcrumb[] = ['id' => $folder->parent_folder->id, 'name' => $folder->parent_folder->filename];

        if ($folder->parent_folder->folder_id !== 0) {
            $this->makeBreadcrumb($folder->parent_folder);
        }
    }
}
