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
            'path' => (string) $this->path,
            'is_image' => $this->isImage(),
            'media_type' => $this->media_type,
            'fa_icon' => FileHelper::getFaIcon($this->media_type),
            'created_at' => $this->created_at,
            'small_thumb' => $this->imagy->getThumbnail($this->path,'smallThumb'),
            'medium_thumb' => $this->imagy->getThumbnail($this->path,'mediumThumb'),
        ];
    }
}
