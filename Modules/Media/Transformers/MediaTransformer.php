<?php

namespace Modules\Media\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Image\Imagy;

class MediaTransformer extends Resource
{
    /**
     * @var Imagy
     */
    private $imagy;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->imagy = app(Imagy::class);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'path' => $this->getPath(),
            'is_image' => $this->isImage(),
            'is_folder' => $this->isFolder(),
            'media_type' => $this->media_type,
            'fa_icon' => FileHelper::getFaIcon($this->media_type),
            'created_at' => $this->created_at,
            'folder_id' => $this->folder_id,
            'small_thumb' => $this->imagy->getThumbnail($this->path, 'smallThumb'),
            'medium_thumb' => $this->imagy->getThumbnail($this->path, 'mediumThumb'),
        ];
    }

    private function getPath()
    {
        if ($this->is_folder) {
            return $this->path->getRelativeUrl();
        }

        return (string) $this->path;
    }
}
