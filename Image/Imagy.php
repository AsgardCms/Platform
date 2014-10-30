<?php namespace Modules\Media\Image;

use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Config\Repository;

class Imagy
{
    /**
     * @var \Intervention\Image\Image
     */
    private $image;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;
    /**
     * @var ImageFactoryInterface
     */
    private $imageFactory;

    public function __construct(Repository $config, ImageFactoryInterface $imageFactory)
    {
        $this->image = App::make('Intervention\Image\ImageManager');
        $this->finder = App::make('Illuminate\Filesystem\Filesystem');
        $this->config = $config;
        $this->imageFactory = $imageFactory;
    }

    /**
     * Get an image in the given thumbnail options
     * @param string $path
     * @param string $thumbnail
     * @param bool $forceCreate
     * @return string
     */
    public function get($path, $thumbnail, $forceCreate = false)
    {
        $filename = '/assets/media/' . $this->newFilename($path, $thumbnail);

        if ($this->returnCreatedFile($filename, $forceCreate)) {
            return $filename;
        }

        $image = $this->image->make(public_path() . $path);

        foreach ($this->config->get("media::thumbnails.{$thumbnail}") as $manipulation => $options) {
            $image = $this->imageFactory->make($manipulation)->handle($image, $options);
        }

        $image = $image->encode(pathinfo($path, PATHINFO_EXTENSION));

        $this->writeImage($filename, $image);

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

    /**
     * Return the already created file if it exists and force create is false
     * @param string $filename
     * @param bool $forceCreate
     * @return bool
     */
    private function returnCreatedFile($filename, $forceCreate)
    {
        return $this->finder->isFile(public_path() . $filename) && !$forceCreate;
    }

    /**
     * Write the given image
     * @param string $filename
     * @param string $image
     */
    private function writeImage($filename, $image)
    {
        $this->finder->put(public_path() . $filename, $image);
    }
}
