<?php namespace Modules\Media\Image;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\App;

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
     * @var ImageFactoryInterface
     */
    private $imageFactory;
    /**
     * @var ThumbnailsManager
     */
    private $manager;

    /**
     * All the different images types where thumbnails should be created
     * @var array
     */
    private $imageExtensions = ['jpg', 'png', 'jpeg', 'gif'];
    /**
     * @var Repository
     */
    private $config;

    /**
     * @param ImageFactoryInterface $imageFactory
     * @param ThumbnailsManager $manager
     * @param Repository $config
     */
    public function __construct(ImageFactoryInterface $imageFactory, ThumbnailsManager $manager, Repository $config)
    {
        $this->image = App::make('Intervention\Image\ImageManager');
        $this->finder = App::make('Illuminate\Filesystem\Filesystem');
        $this->imageFactory = $imageFactory;
        $this->manager = $manager;
        $this->config = $config;
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
        if (!$this->isImage($path)) {
            return;
        }

        $filename = $this->config->get('media::config.files-path') . $this->newFilename($path, $thumbnail);

        if ($this->returnCreatedFile($filename, $forceCreate)) {
            return $filename;
        }

        $this->makeNew($path, $filename, $thumbnail);

        return $filename;
    }

    /**
     * Return the thumbnail path
     * @param string $originalImage
     * @param string $thumbnail
     * @return string
     */
    public function getThumbnail($originalImage, $thumbnail)
    {
        if (!$this->isImage($originalImage)) {
            return $originalImage;
        }

        return $this->config->get('media::config.files-path') . $this->newFilename($originalImage, $thumbnail);
    }

    /**
     * Create all thumbnails for the given image path
     * @param string $path
     */
    public function createAll($path)
    {
        if (!$this->isImage($path)) {
            return;
        }

        foreach ($this->manager->all() as $thumbName => $filters) {
            $image = $this->image->make(public_path() . $path);
            $filename = $this->config->get('media::config.files-path') . $this->newFilename($path, $thumbName);
            foreach ($filters as $manipulation => $options) {
                $image = $this->imageFactory->make($manipulation)->handle($image, $options);
            }
            $image = $image->encode(pathinfo($path, PATHINFO_EXTENSION));
            $this->writeImage($filename, $image);
        }
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

    /**
     * Make a new image
     * @param string $path
     * @param string $filename
     * @param string null $thumbnail
     */
    private function makeNew($path, $filename, $thumbnail)
    {
        $image = $this->image->make(public_path() . $path);

        foreach ($this->manager->find($thumbnail) as $manipulation => $options) {
            $image = $this->imageFactory->make($manipulation)->handle($image, $options);
        }

        $image = $image->encode(pathinfo($path, PATHINFO_EXTENSION));
        $this->writeImage($filename, $image);
    }

    /**
     * Check if the given path is en image
     * @param string $path
     * @return bool
     */
    public function isImage($path)
    {
        return in_array(pathinfo($path, PATHINFO_EXTENSION), $this->imageExtensions);
    }

    /**
     * Delete all files on disk for the given file in storage
     * This means the original and the thumbnails
     * @param $file
     * @return bool
     */
    public function deleteAllFor($file)
    {
        if (!$this->isImage($file->path)) {
            return $this->finder->delete($file->path);
        }

        $paths[] = public_path() . $file->path;
        $fileName = pathinfo($file->path, PATHINFO_FILENAME);
        $extension = pathinfo($file->path, PATHINFO_EXTENSION);
        foreach ($this->manager->all() as $thumbnail => $filters) {
            $paths[] = public_path() . $this->config->get(
                    'media::config.files-path'
                ) . "{$fileName}_{$thumbnail}.{$extension}";
        }
        return $this->finder->delete($paths);
    }

}
