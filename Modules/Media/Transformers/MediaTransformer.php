<?php

namespace Modules\Media\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;

class MediaTransformer extends JsonResource
{
    /**
     * @var Imagy
     */
    private $imagy;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailManager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->imagy = app(Imagy::class);
        $this->thumbnailManager = app(ThumbnailManager::class);
    }

    public function toArray($request)
    {
        $data = [
            'id' => $this->resource->id,
            'filename' => $this->resource->filename,
            'path' => $this->getPath(),
            'is_image' => $this->resource->isImage(),
            'is_folder' => $this->resource->isFolder(),
            'media_type' => $this->resource->media_type,
            'fa_icon' => FileHelper::getFaIcon($this->resource->media_type),
            'created_at' => $this->resource->created_at,
            'folder_id' => $this->resource->folder_id,
            'small_thumb' => $this->imagy->getThumbnail($this->resource->path, 'smallThumb'),
            'medium_thumb' => $this->imagy->getThumbnail($this->resource->path, 'mediumThumb'),
            'urls' => [
                'delete_url' => $this->getDeleteUrl(),
            ],
        ];

        foreach ($this->thumbnailManager->all() as $thumbnail) {
            $thumbnailName = $thumbnail->name();

            $data['thumbnails'][] = [
                'name' => $thumbnailName,
                'path' => $this->imagy->getThumbnail($this->resource->path, $thumbnailName),
                'size' => $thumbnail->size(),
            ];
        }

        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $data[$locale] = [];
            foreach ($this->resource->translatedAttributes as $translatedAttribute) {
                $data[$locale][$translatedAttribute] = $this->resource->translateOrNew($locale)->$translatedAttribute;
            }
        }

        foreach ($this->resource->tags as $tag) {
            $data['tags'][] = $tag->name;
        }

        return $data;
    }

    private function getPath()
    {
        if ($this->resource->isFolder()) {
            return $this->resource->path->getRelativeUrl();
        }

        return (string) $this->resource->path;
    }

    private function getDeleteUrl()
    {
        if ($this->resource->isImage()) {
            return route('api.media.media.destroy', $this->resource->id);
        }

        return route('api.media.folders.destroy', $this->resource->id);
    }
}
