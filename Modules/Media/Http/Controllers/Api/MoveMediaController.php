<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Media\Entities\File;
use Modules\Media\Http\Requests\MoveMediaRequest;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\Movers\Mover;

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
    /**
     * @var Mover
     */
    private $mover;

    public function __construct(
        FileRepository $file,
        FolderRepository $folder,
        Mover $mover
    )
    {
        $this->file = $file;
        $this->folder = $folder;
        $this->mover = $mover;
    }

    public function __invoke(MoveMediaRequest $request)
    {
        $destination = $this->getDestinationFolder($request->get('destinationFolder'));

        $failedMoves = 0;
        foreach ($request->get('files') as $file) {
            $failedMoves = $this->mover->move($this->file->find($file['id']), $destination);
        }

        return response()->json([
            'errors' => $failedMoves > 0,
            'message' => $failedMoves > 0 ? 'Some files were not moved' : 'Files moved successfully',
            'folder_id' => $destination->id,
        ]);
    }

    private function makeRootFolder() : File
    {
        return new File([
            'id' => 0,
            'folder_id' => 0,
            'path' => config('asgard.media.config.files-path'),
        ]);
    }

    /**
     * @param int $destinationFolderId
     * @return File
     */
    protected function getDestinationFolder($destinationFolderId) : File
    {
        $destination = $this->folder->findFolder($destinationFolderId);
        if ($destination === null) {
            $destination = $this->makeRootFolder();
        }

        return $destination;
    }
}
