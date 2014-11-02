<?php namespace Modules\Media\Services;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\Queue;
use Modules\Media\Image\Facade\Imagy;
use Modules\Media\Repositories\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    /**
     * @var FileRepository
     */
    private $file;
    /**
     * @var Repository
     */
    private $config;
    /**
     * @var Queue
     */
    private $queue;

    public function __construct(FileRepository $file, Repository $config, Queue $queue)
    {
        $this->file = $file;
        $this->config = $config;
        $this->queue = $queue;
    }

    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public function store(UploadedFile $file)
    {
        // Save the file info to db
        $savedFile = $this->file->createFromFile($file);

        // Move the uploaded file to files path
        $file->move(public_path() . $this->config->get('media::config.files-path'), $savedFile->filename);

        $this->createThumbnails($savedFile);

        return $savedFile;
    }

    /**
     * Create the necessary thumbnails for the given file
     * @param $savedFile
     */
    private function createThumbnails($savedFile)
    {
        $this->queue->push(function($job) use ($savedFile)
        {
            Imagy::createAll($savedFile->path);
            $job->delete();
        });
    }

}
