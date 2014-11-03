<?php namespace Modules\Media\Services;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\App;
use Modules\Media\Image\Imagy;
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
    /**
     * @var Imagy
     */
    private $imagy;

    public function __construct(
        FileRepository $file,
        Repository $config,
        Queue $queue,
        Imagy $imagy)
    {
        $this->file = $file;
        $this->config = $config;
        $this->queue = $queue;
        $this->imagy = $imagy;
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
        $this->queue->push(function(Job $job) use ($savedFile)
        {
            App::make('imagy')->createAll($savedFile->path);
            $job->delete();
        });
    }

}
