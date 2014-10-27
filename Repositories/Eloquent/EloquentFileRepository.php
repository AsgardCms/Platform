<?php namespace Modules\Media\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Repositories\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EloquentFileRepository extends EloquentBaseRepository implements FileRepository
{
    /**
     * Update a resource
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
    }

    /**
     * Create a file row from the given file
     * @param UploadedFile $file
     * @return mixed
     */
    public function createFromFile(UploadedFile $file)
    {
        $this->model->create([
            'filename' => ''
        ]);
    }
}
