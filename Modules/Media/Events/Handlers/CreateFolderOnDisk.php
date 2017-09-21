<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Contracts\Filesystem\Factory;
use Modules\Media\Events\FolderWasCreated;

class CreateFolderOnDisk
{
    /**
     * @var Factory
     */
    private $filesystem;

    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function handle(FolderWasCreated $event)
    {
        $this->filesystem->disk($this->getConfiguredFilesystem())->makeDirectory($event->folder->path->getRelativeUrl());
    }

    /**
     * @return string
     */
    private function getConfiguredFilesystem()
    {
        return config('asgard.media.config.filesystem');
    }
}
