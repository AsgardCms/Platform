<?php namespace Modules\Media\Services;

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
     * @var Imagy
     */
    private $imagy;

    public function __construct(FileRepository $file, Imagy $imagy)
    {
        $this->file = $file;
        $this->imagy = $imagy;
    }

    public function store(UploadedFile $file)
    {
        // Save the file info to db
        $savedFile = $this->file->createFromFile($file);

        // Move the uploaded file to /public/assets/media/
        $file->move(public_path() . '/assets/media', $savedFile->filename);

        // Create the thumbnails
        $this->imagy->createAll($savedFile->path);

        return $savedFile;
    }

}
