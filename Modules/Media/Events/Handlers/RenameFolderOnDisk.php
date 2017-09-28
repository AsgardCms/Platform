<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Contracts\Filesystem\Factory;
use Modules\Media\Events\FolderWasUpdated;
use Modules\Media\Repositories\FileRepository;

class RenameFolderOnDisk
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

    public function handle(FolderWasUpdated $event)
    {
        $this->renameFolder($event);
        $this->renameDatabaseReferences($event);
    }

    private function renameDatabaseReferences(FolderWasUpdated $event)
    {
        $previousPath = $event->previousFolderData['path']->getRelativeUrl();
        $newPath = $event->folder->path->getRelativeUrl();

        $this->replacePathReferences($event->folder->id, $previousPath, $newPath);
    }

    private function renameFolder($event)
    {
        $this->filesystem->disk($this->getConfiguredFilesystem())
            ->move(
                $this->getDestinationPath($event->previousFolderData['path']->getRelativeUrl()),
                $this->getDestinationPath($event->folder->path->getRelativeUrl())
            );
    }

    private function getDestinationPath($path)
    {
        if ($this->getConfiguredFilesystem() === 'local') {
            return basename(public_path()) . $path;
        }

        return $path;
    }

    /**
     * @return string
     */
    private function getConfiguredFilesystem()
    {
        return config('asgard.media.config.filesystem');
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
}
