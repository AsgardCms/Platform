<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Contracts\Filesystem\Factory;
use Modules\Media\Events\FileStartedMoving;
use Modules\Media\Image\Thumbnail;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\ValueObjects\MediaPath;

class MoveFileOnDisk
{
    /**
     * @var Factory
     */
    private $filesystem;
    /**
     * All the different images types where thumbnails should be created
     * @var array
     */
    private $imageExtensions = ['jpg', 'png', 'jpeg', 'gif'];
    /**
     * @var ThumbnailManager
     */
    private $manager;

    public function __construct(Factory $filesystem, ThumbnailManager $manager)
    {
        $this->filesystem = $filesystem;
        $this->manager = $manager;
    }

    public function handle(FileStartedMoving $event)
    {
        $this->moveOriginal($event);

        if ($this->isImage($event->previousData['path']->getRelativeUrl())) {
            $this->moveThumbnails($event);
        }
    }

    private function moveOriginal($event)
    {
        $this->move($event->previousData['path']->getRelativeUrl(), $event->file->path->getRelativeUrl());
    }

    private function moveThumbnails($event)
    {
        foreach ($this->manager->all() as $thumbnail) {
            $fromPath = $this->getFilenameFor($event->previousData['path'], $thumbnail);
            $toPath = $this->getFilenameFor($event->file->path, $thumbnail);

            $this->move($fromPath, $toPath);
        }
    }

    private function move($fromPath, $toPath)
    {
        $this->filesystem->disk($this->getConfiguredFilesystem())
            ->move(
                $this->getDestinationPath($fromPath),
                $this->getDestinationPath($toPath)
            );
    }

    /**
     * Check if the given path is en image
     * @param  string $path
     * @return bool
     */
    private function isImage($path)
    {
        return in_array(pathinfo($path, PATHINFO_EXTENSION), $this->imageExtensions);
    }

    private function getDestinationPath($path) : string
    {
        if ($this->getConfiguredFilesystem() === 'local') {
            return basename(public_path()) . $path;
        }

        return $path;
    }

    /**
     * @return string
     */
    private function getConfiguredFilesystem() : string
    {
        return config('asgard.media.config.filesystem');
    }

    /**
     * @param MediaPath $path
     * @param Thumbnail|string $thumbnail
     * @return string
     */
    private function getFilenameFor(MediaPath $path, $thumbnail)
    {
        if ($thumbnail instanceof  Thumbnail) {
            $thumbnail = $thumbnail->name();
        }
        $filenameWithoutPrefix = $this->removeConfigPrefix($path->getRelativeUrl());
        $filename = substr(strrchr($filenameWithoutPrefix, '/'), 1);
        $folders = str_replace($filename, '', $filenameWithoutPrefix);

        if ($filename === false) {
            return config('asgard.media.config.files-path') . $this->newFilename($path, $thumbnail);
        }

        return config('asgard.media.config.files-path') . $folders . $this->newFilename($path, $thumbnail);
    }

    /**
     * @param string $path
     * @return string
     */
    private function removeConfigPrefix(string $path) : string
    {
        $configAssetPath = config('asgard.media.config.files-path');

        return str_replace([
            $configAssetPath,
            ltrim($configAssetPath, '/'),
        ], '', $path);
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
}
