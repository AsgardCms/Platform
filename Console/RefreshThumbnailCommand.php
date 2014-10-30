<?php namespace Modules\Media\Console;

use Illuminate\Console\Command;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\FileRepository;

class RefreshThumbnailCommand extends Command
{
    protected $name = 'media:thumb-refresh';
    protected $description = 'Create and or refresh the thumbnails';
    /**
     * @var Imagy
     */
    private $imagy;
    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(Imagy $imagy, FileRepository $file)
    {
        parent::__construct();
        $this->imagy = $imagy;
        $this->file = $file;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->line('Preparing to regenerate all thumbnails...');

        foreach ($this->file->all() as $file) {
            $this->imagy->createAll($file->path);
        }

        $this->info('All thumbnails refreshed');
    }

}
