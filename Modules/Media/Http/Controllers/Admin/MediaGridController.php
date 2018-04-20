<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Transformers\MediaTransformer;

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
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function ckIndex(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $cols = $request->get('columns');
            $start = $request->get('start', 0);
            $length = $request->get('length', 25);
            $page = ($start/$length) + 1;
            $requestData = ['per_page' => $length, 'page' => $page];
            foreach ($cols as $col) {
                if ($col['searchable'] && isset($col['search']['value'])) {
                    $requestData[$col['data']] = $col['search']['value'];
                }
            }
            $request->merge($requestData);
            $files = $this->file->pagingForGrid($request);

            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $files->total(),
                "recordsFiltered" => $files->total(),
                'data' => MediaTransformer::collection($files)
            ];
            return response()->json($output);

        }
        return view('media::admin.grid.ckeditor');
    }
}
