<?php

namespace Modules\Media\Services\Movers;

use Illuminate\Contracts\Filesystem\Factory;
use League\Flysystem\FileExistsException;
use Modules\Media\Entities\File;
use Modules\Media\Image\Thumbnail;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;

final class FileMover implements MoverInterface
{
    /**
     * All the different images types where thumbnails should be created
     * @var array
     */
    private $imageExtensions = ['jpg', 'png', 'jpeg', 'gif'];
    /**
     * @var Factory
     */
    private $filesystem;
    private $fromPath;
    private $toPath;
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var ThumbnailManager
     */
    private $manager;

    public function __construct(Factory $filesystem, FileRepository $file, ThumbnailManager $manager)
    {
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->manager = $manager;
    }

    public function move(File $file, File $destination): bool
    {
        $movedOnDisk = $this->moveOriginalOnDisk($file, $destination);

        if ($movedOnDisk === false) {
            return false;
        }

        $file = $this->moveDatabase($file, $destination);

        if ($this->isImage($this->fromPath)) {
            $this->moveThumbnails();
        }

        return true;
    }

    private function moveThumbnails()
    {
        foreach ($this->manager->all() as $thumbnail) {
            $fromPath = $this->getFilenameFor($this->fromPath, $thumbnail);
            $toPath = $this->getFilenameFor($this->toPath, $thumbnail);

            $this->moveFile($fromPath, $toPath);
        }
    }

    private function moveOriginalOnDisk(File $folder, File $destination) : bool
    {
        $this->fromPath = $folder->path->getRelativeUrl();
        $this->toPath = $this->getNewPathFor($folder->filename, $destination->id);

        return $this->moveFile($this->fromPath, $this->toPath);
    }

    private function moveDatabase(File $file, File $destination) : File
    {
        return $this->file->move($file, $destination);
    }

    private function moveFile($fromPath, $toPath) : bool
    {
        try {
            $this->filesystem->disk($this->getConfiguredFilesystem())
                ->move(
                    $this->getDestinationPath($fromPath),
                    $this->getDestinationPath($toPath)
                );
        } catch (FileExistsException $e) {
            return false;
        }

        return true;
    }

    private function getDestinationPath($path) : string
    {
        if ($this->getConfiguredFilesystem() === 'local') {
            return basename(public_path()) . $path;
        }

        return $path;
    }

    private function getConfiguredFilesystem() : string
    {
        return config('asgard.media.config.filesystem');
    }

    private function getNewPathFor(string $filename, int $folderId)
    {
        if ($folderId !== 0) {
            $parent = app(FolderRepository::class)->findFolder($folderId);
            if ($parent !== null) {
                return $parent->path->getRelativeUrl() . '/' . $filename;
            }
        }

        return config('asgard.media.config.files-path') . $filename;
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

    /**
     * @param string $path
     * @param Thumbnail|string $thumbnail
     * @return string
     */
    private function getFilenameFor(string $path, $thumbnail)
    {
        if ($thumbnail instanceof Thumbnail) {
            $thumbnail = $thumbnail->name();
        }
        $filenameWithoutPrefix = $this->removeConfigPrefix($path);
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
