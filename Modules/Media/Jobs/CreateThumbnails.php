<?php

namespace Modules\Media\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Media\ValueObjects\MediaPath;

class CreateThumbnails implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var MediaPath
     */
    private $path;

    public function __construct(MediaPath $path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        $imagy = app('imagy');

        app('log')->info('Generating thumbnails for path: ' . $this->path);

        $imagy->createAll($this->path);
    }
}
