<?php

namespace Modules\Media\Http\Controllers\Admin;

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
    public function index()
    {
        $files = $this->file->allForGrid();
        $thumbnails = $this->thumbnailsManager->all();

        return view('media::admin.grid.general', compact('files', 'thumbnails'));
    }

    /**
     * A grid view of uploaded files used for the wysiwyg editor
     * @return \Illuminate\View\View
     */
    public function ckIndex()
    {
        $files = $this->file->allForGrid();
        $thumbnails = $this->thumbnailsManager->all();

        return view('media::admin.grid.ckeditor', compact('files', 'thumbnails'));
    }
}
