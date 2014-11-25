<?php namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Support\Facades\Response;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Services\FileService;

class MediaController
{
    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadMediaRequest $request
     * @return Response
     */
    public function store(UploadMediaRequest $request)
    {
        $savedFile = $this->fileService->store($request->file('file'));

        return Response::json($savedFile->toArray());
    }
}
