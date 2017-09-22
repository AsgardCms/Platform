<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Contracts\Filesystem\Factory;
use Modules\Media\Events\FolderWasUpdated;

class RenameFolderOnDisk
{
    /**
     * @var Factory
     */
    private $filesystem;

    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    public function handle(FolderWasUpdated $event)
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
}
