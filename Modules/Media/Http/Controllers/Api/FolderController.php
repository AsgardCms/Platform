<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Media\Http\Requests\CreateFolderRequest;
use Modules\Media\Repositories\FolderRepository;

class FolderController extends Controller
{
    /**
     * @var FolderRepository
     */
    private $folder;

    public function __construct(FolderRepository $folder)
    {
        $this->folder = $folder;
    }

    public function store(CreateFolderRequest $request)
    {
        $this->folder->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('media::folders.folder was created'),
        ]);
    }
}
