<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;

class BatchDestroyController extends Controller
{
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var FolderRepository
     */
    private $folder;
    /**
     * @var Imagy
     */
    private $imagy;

    public function __construct(FileRepository $file, FolderRepository $folder, Imagy $imagy)
    {
        $this->file = $file;
        $this->folder = $folder;
        $this->imagy = $imagy;
    }

    public function __invoke(Request $request)
    {
        foreach ($request->get('files') as $file) {
            if ($file['is_folder'] === true) {
                $this->deleteFolder($file['id']);
                continue;
            }
            $this->deleteFile($file['id']);
        }

        return response()->json([
            'errors' => false,
            'message' => trans('media::messages.selected items deleted'),
        ]);
    }

    private function deleteFile($fileId)
    {
        $file = $this->file->find($fileId);

        if ($file === null) {
            return;
        }

        $this->imagy->deleteAllFor($file);
        $this->file->destroy($file);
    }

    private function deleteFolder($folderId)
    {
        $folder = $this->folder->findFolder($folderId);

        if ($folder === null) {
            return;
        }

        $this->folder->destroy($folder);
    }
}
