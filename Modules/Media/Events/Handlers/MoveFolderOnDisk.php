<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Contracts\Filesystem\Factory;
use Modules\Media\Events\FolderStartedMoving;
use Modules\Media\Repositories\FileRepository;

class MoveFolderOnDisk
{
    /**
     * @var Factory
     */
    private $filesystem;
    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(Factory $filesystem, FileRepository $file)
    {
        $this->filesystem = $filesystem;
        $this->file = $file;
    }

    public function handle(FolderStartedMoving $event)
    {
        $this->moveOriginal($event);

        $this->renameDatabaseReferences($event);
    }

    private function moveOriginal(FolderStartedMoving $event)
    {
        $this->move($event->previousData['path']->getRelativeUrl(), $event->folder->path->getRelativeUrl());
    }

    private function renameDatabaseReferences(FolderStartedMoving $event)
    {
        $previousPath = $event->previousData['path']->getRelativeUrl();
        $newPath = $event->folder->path->getRelativeUrl();

        $this->replacePathReferences($event->folder->id, $previousPath, $newPath);
    }

    private function replacePathReferences($folderId, $previousPath, $newPath)
    {
        $medias = $this->file->allChildrenOf($folderId);

        foreach ($medias as $media) {
            $oldPath = $media->path->getRelativeUrl();

            $media->update([
                'path' => str_replace($previousPath, $newPath, $oldPath),
            ]);
            if ($media->isFolder() === true) {
                $this->replacePathReferences($media->id, $previousPath, $newPath);
            }
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
}
