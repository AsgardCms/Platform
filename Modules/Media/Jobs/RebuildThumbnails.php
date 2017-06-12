<?php

namespace Modules\Media\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class RebuildThumbnails implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Collection
     */
    private $paths;

    public function __construct(Collection $paths)
    {
        $this->paths = $paths;
    }

    public function handle()
    {
        $imagy = app('imagy');

        foreach ($this->paths as $path) {
            try {
                $imagy->createAll($path);
                app('log')->info('Generating thumbnails for path: ' . $path);
            } catch (\Exception $e) {
                app('log')->warning('File not found: ' . $path);
            }
        }
    }
}
