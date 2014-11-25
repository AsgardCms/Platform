<?php namespace Modules\Media\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileRepository extends BaseRepository
{
    /**
     * Create a file row from the given file
     * @param UploadedFile $file
     * @return mixed
     */
    public function createFromFile(UploadedFile $file);
}
