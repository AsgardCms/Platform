<?php

namespace Modules\Media\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Modules\Media\Repositories\FileRepository;

class MediaController extends Controller
{
    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(FileRepository $file)
    {
        $this->file = $file;
    }

    public function show($path)
    {
        $file = $this->file->findForVirtualPath($path);
        $type = $file->mimetype;

        $path = storage_path('app' . $file->path->getRelativeUrl());

        //return Image::make($path)->response();

        return response()->file($path, [
            'Content-Type' => $type,
        ]);
    }
}
