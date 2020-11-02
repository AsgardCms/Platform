<?php

namespace Modules\Media\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Media\Entities\File;
use Modules\Media\Events\FileWasLinked;
use Modules\Media\Events\FileWasUnlinked;
use Modules\Media\Events\FileWasUploaded;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Http\Requests\UploadDropzoneMediaRequest;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Transformers\MediaTransformer;
use Yajra\DataTables\Facades\DataTables;

class MediaController extends Controller
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var FileRepository
     */
    private $file;

    /**
     * @var Imagy
     */
    private $imagy;

    public function __construct(FileService $fileService, FileRepository $file, Imagy $imagy)
    {
        $this->fileService = $fileService;
        $this->file = $file;
        $this->imagy = $imagy;
    }

    public function all()
    {
        $files = $this->file->allWithBuilder();

        return Datatables::eloquent($files)
            ->addColumn('thumbnail', function ($file) {
                if ($file->isFolder()) {
                    return '<i class="fa fa-folder" style="font-size: 20px;"></i>';
                }
                if ($file->isImage()) {
                    return '<img src="' . $this->imagy->getThumbnail($file->path, 'smallThumb') . '"/>';
                }

                return '<i class="fa ' . FileHelper::getFaIcon($file->media_type) . '" style="font-size: 20px;"></i>';
            })
            ->rawColumns(['thumbnail'])
            ->toJson();
    }

    public function allVue(Request $request)
    {
        return MediaTransformer::collection($this->file->serverPaginationFilteringFor($request));
    }

    public function find(File $file)
    {
        return new MediaTransformer($file);
    }

    public function findFirstByZoneEntity(Request $request)
    {
        $imageable = DB::table('media__imageables')
            ->where('imageable_id', $request->get('entity_id'))
            ->whereZone($request->get('zone'))
            ->whereImageableType($request->get('entity'))
            ->first();

        if ($imageable === null) {
            return response()->json(null);
        }

        $file = $this->file->find($imageable->file_id);

        if ($file === null) {
            return response()->json(['data' => null]);
        }

        return new MediaTransformer($file);
    }

    /**
     * Get a media collection by zone and entity object. Require some params that were passed to request: entity (Full class name of entity), entity_id and zone
     *
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByZoneEntity(Request $request)
    {
        $entityName = (string)$request->get('entity');
        $entityModel = new $entityName;
        $entity = $entityModel::find($request->get('entity_id'));
        if ($entity && in_array('Modules\Media\Support\Traits\MediaRelation', class_uses($entity)) && $entity->files()->count()) {
            $files = $this->file->findMultipleFilesByZoneForEntity($request->get('zone'), $entity);

            return MediaTransformer::collection($files);
        }

        return response()->json(['data' => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UploadMediaRequest $request) : JsonResponse
    {
        $savedFile = $this->fileService->store($request->file('file'), $request->get('parent_id'));

        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }

        event(new FileWasUploaded($savedFile));

        return response()->json($savedFile->toArray());
    }

    public function storeDropzone(UploadDropzoneMediaRequest $request) : JsonResponse
    {
        $savedFile = $this->fileService->store($request->file('file'));

        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }

        event(new FileWasUploaded($savedFile));

        return response()->json($savedFile->toArray());
    }

    public function update(File $file, Request $request)
    {
        $data = $request->except(['filename', 'path', 'extension', 'size', 'id', 'thumbnails']);

        $this->file->update($file, $data);

        return response()->json([
            'errors' => false,
            'message' => trans('media::messages.file updated'),
        ]);
    }

    /**
     * Link the given entity with a media file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkMedia(Request $request) : JsonResponse
    {
        $mediaId = $request->get('mediaId');
        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');
        $order = $request->get('order');

        $entity = $entityClass::find($entityId);
        $zone = $request->get('zone');
        $entity->files()->attach($mediaId, [
            'imageable_type' => $entityClass,
            'zone' => $zone,
            'order' => $order,
        ]);
        $imageable = DB::table('media__imageables')->whereFileId($mediaId)
            ->whereZone($zone)
            ->whereImageableType($entityClass)
            ->first();
        $file = $this->file->find($imageable->file_id);

        $mediaType = FileHelper::getTypeByMimetype($file->mimetype);

        $thumbnailPath = $this->getThumbnailPathFor($mediaType, $file);

        event(new FileWasLinked($file, $entity));

        return response()->json([
            'error' => false,
            'message' => 'The link has been added.',
            'result' => [
                'path' => $thumbnailPath,
                'imageableId' => $imageable->id,
                'mediaType' => $mediaType,
                'mimetype' => $file->mimetype,
            ],
        ]);
    }

    /**
     * Remove the record in the media__imageables table for the given id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkMedia(Request $request) : JsonResponse
    {
        $imageableId = $request->get('imageableId');
        $deleted = DB::table('media__imageables')->whereId($imageableId)->delete();
        if (! $deleted) {
            return response()->json([
                'error' => true,
                'message' => 'The file was not found.',
            ]);
        }

        event(new FileWasUnlinked($imageableId));

        return response()->json([
            'error' => false,
            'message' => 'The link has been removed.',
        ]);
    }

    /**
     * Sort the record in the media__imageables table for the given array
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortMedia(Request $request) : JsonResponse
    {
        $imageableIdArray = $request->get('sortable');

        $order = 1;

        foreach ($imageableIdArray as $id) {
            DB::table('media__imageables')->whereId($id)->update(['order' => $order]);
            $order++;
        }

        return response()->json(['error' => false, 'message' => 'The items have been reorder.']);
    }

    public function destroy(File $file)
    {
        $this->imagy->deleteAllFor($file);
        $this->file->destroy($file);

        return response()->json([
            'errors' => false,
            'message' => trans('media::messages.file deleted'),
        ]);
    }

    /**
     * Get the path for the given file and type
     * @param string $mediaType
     * @param File $file
     * @return string
     */
    private function getThumbnailPathFor($mediaType, File $file) : string
    {
        if ($mediaType === 'image') {
            return $this->imagy->getThumbnail($file->path, 'mediumThumb');
        }

        return $file->path->getRelativeUrl();
    }
}
