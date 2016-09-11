<?php

namespace Modules\Media\Services;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Media\Entities\File;
use Modules\Media\Jobs\CreateThumbnails;
use Modules\Media\Repositories\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    use DispatchesJobs;

    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var Factory
     */
    private $filesystem;

    public function __construct(FileRepository $file, Factory $filesystem)
    {
        $this->file = $file;
        $this->filesystem = $filesystem;
    }

    /**
     * @param  UploadedFile $file
     * @return mixed
     */
    public function store(UploadedFile $file)
    {
        $savedFile = $this->file->createFromFile($file);

        $path = $this->getDestinationPath($savedFile->getOriginal('path'));
        $stream = fopen($file->getRealPath(), 'r+');
        $this->filesystem->disk($this->getConfiguredFilesystem())->writeStream($path, $stream, [
            'visibility' => 'public',
            'mimetype' => $savedFile->mimetype,
        ]);

        $this->createThumbnails($savedFile);

        return $savedFile;
    }

    /**
     * Create the necessary thumbnails for the given file
     * @param $savedFile
     */
    private function createThumbnails(File $savedFile)
    {
        $this->dispatch(new CreateThumbnails($savedFile->path));
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
