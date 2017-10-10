<?php

namespace Modules\Media\Services\Movers;

use Illuminate\Contracts\Filesystem\Factory;
use League\Flysystem\FileExistsException;
use Modules\Media\Entities\File;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;

final class FolderMover implements MoverInterface
{
    /**
     * @var Factory
     */
    private $filesystem;
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var FolderRepository
     */
    private $folder;
    private $fromPath;
    private $toPath;

    public function __construct(Factory $filesystem, FileRepository $file, FolderRepository $folder)
    {
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->folder = $folder;
    }

    public function move(File $folder, File $destination) : bool
    {
        $movedOnDisk = $this->moveOriginalOnDisk($folder, $destination);

        if ($movedOnDisk === false) {
            return false;
        }
        $folder = $this->moveDatabase($folder, $destination);

        $this->renameDatabaseReferences($folder);

        return true;
    }

    private function moveOriginalOnDisk(File $folder, File $destination) : bool
    {
        $this->fromPath = $folder->path->getRelativeUrl();
        $this->toPath = $this->getNewPathFor($folder->filename, $destination);

        return $this->moveDirectory($this->fromPath, $this->toPath);
    }

    private function moveDatabase(File $folder, File $destination) : File
    {
        return $this->folder->move($folder, $destination);
    }

    private function renameDatabaseReferences(File $folder)
    {
        $this->replacePathReferences($folder->id, $this->fromPath, $this->toPath);
    }

    private function replacePathReferences($folderId, $previousPath, $newPath)
    {
        $medias = $this->file->allChildrenOf($folderId);

        foreach ($medias as $media) {
            $oldPath = $media->path->getRelativeUrl();

            $media->update([
                'path' => $this->removeDoubleSlashes(str_replace($previousPath, $newPath, $oldPath)),
            ]);
            if ($media->isFolder() === true) {
                $this->replacePathReferences($media->id, $previousPath, $newPath);
            }
        }
    }

    private function moveDirectory($fromPath, $toPath) : bool
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

    private function getNewPathFor(string $filename, File $folder) : string
    {
        return $this->removeDoubleSlashes($folder->path->getRelativeUrl() . '/' . str_slug($filename));
    }

    private function removeDoubleSlashes(string $string) : string
    {
        return str_replace('//', '/', $string);
    }
}
