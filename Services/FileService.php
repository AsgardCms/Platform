<?php namespace Modules\Media\Services;

use Modules\Media\Repositories\FileRepository;

class FileService
{
    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(FileRepository $file)
    {
        $this->file = $file;
    }

    public function store($file)
    {
        // Save the file info to db
        $savedFile = $this->file->createFromFile($file);

        // Move the uploaded file to /public/assets/media/
        $file->move(public_path() . '/assets/media', $savedFile->filename);

        return $savedFile;
    }

}
