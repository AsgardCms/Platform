<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Contracts\Filesystem\Factory;
use Modules\Media\Events\FolderIsDeleting;

class DeleteFolderOnDisk
{
    /**
     * @var Factory
     */
    private $finder;

    public function __construct(Factory $finder)
    {
        $this->finder = $finder;
    }

    public function handle(FolderIsDeleting $event)
    {
        $this->finder->disk($this->getConfiguredFilesystem())->deleteDirectory($this->getDestinationPath($event->folder->getOriginal('path')));
    }

    /**
     * @param string $path
     * @return string
     */
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
