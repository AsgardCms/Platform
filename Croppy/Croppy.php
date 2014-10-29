<?php namespace Modules\Media\Croppy;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\App;
use Intervention\Image\Image;

class Croppy
{
    /**
     * @var Image
     */
    private $image;
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Filesystem $finder, Repository $config)
    {
        $this->image = App::make('Intervention\Image\ImageManager');
        $this->finder = App::make('Illuminate\Filesystem\Filesystem');
        $this->config = $config;
    }

    public function image($path, $thumbnail)
    {
        $filename = '/assets/media/' . $this->newFilename($path, $thumbnail);
        try {
            $this->finder->get(public_path(). $filename);
            return $filename;
        } catch (FileNotFoundException $e) {
        }

        $image = $this->makeImage($path, $thumbnail);

        $this->finder->put(public_path() . $filename, $image);

        return $filename;
    }

    /**
     * Prepend the thumbnail name to filename
     * @param $path
     * @param $thumbnail
     * @return mixed|string
     */
    private function newFilename($path, $thumbnail)
    {
        $filename = pathinfo($path, PATHINFO_FILENAME);

        return $filename . '_' . $thumbnail . '.' . pathinfo($path, PATHINFO_EXTENSION);
    }

    private function makeImage($path, $thumbnail)
    {
        $thumbnailActions = $this->config->get("media::thumbnails.{$thumbnail}");

        $image = $this->image->make(public_path() . $path);

        return $image->crop($thumbnailActions['crop']['width'], $thumbnailActions['crop']['height'])
            ->encode(pathinfo($path, PATHINFO_EXTENSION));
    }

}
