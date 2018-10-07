<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\FileRepository;

class MediaGridController extends AdminBaseController
{
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailsManager;

    public function __construct(FileRepository $file, ThumbnailManager $thumbnailsManager)
    {
        parent::__construct();

        $this->file = $file;
        $this->thumbnailsManager = $thumbnailsManager;
    }

    /**
     * A grid view for the upload button
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $request->merge(['folder_id' => 0]);
        $files = $this->file->serverPaginationFilteringFor($request);
        $thumbnails = $this->thumbnailsManager->all();

        return view('media::admin.grid.general', compact('files', 'thumbnails'));
    }

    /**
     * A grid view of uploaded files used for the wysiwyg editor
     * @return \Illuminate\View\View
     */
    public function ckIndex()
    {
        $request->merge(['folder_id' => 0]);
        $files = $this->file->serverPaginationFilteringFor($request);
        $thumbnails = $this->thumbnailsManager->all();

        return view('media::admin.grid.ckeditor', compact('files', 'thumbnails'));
    }
}
