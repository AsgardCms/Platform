<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Media\Entities\File;
use Modules\Media\Http\Requests\MoveMediaRequest;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;

class MoveMediaController extends Controller
{
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var FolderRepository
     */
    private $folder;

    public function __construct(FileRepository $file, FolderRepository $folder)
    {
        $this->file = $file;
        $this->folder = $folder;
    }

    public function __invoke(MoveMediaRequest $request)
    {
        $destination = $this->folder->findFolder($request->get('destinationFolder'));
        if ($destination === null) {
            $destination = $this->makeRootFolder();
        }

        foreach ($request->get('files') as $file) {
            $file = $this->file->find($file['id']);

            if ($file->is_folder === false) {
                $this->file->move($file, $destination);
            }
        }

        return response()->json([
            'errors' => false,
            'message' => 'Files moved successfully',
            'folder_id' => $destination->id,
        ]);
    }

    private function makeRootFolder() : File
    {
        return new File([
            'id' => 0,
            'folder_id' => 0,
        ]);
    }
}
