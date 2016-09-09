<?php

namespace Modules\Page\Services;

use Symfony\Component\Finder\Finder;

class FinderService
{
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = Finder::create()->files();
    }

    /**
     * @param  array $excludes
     *
     * @return $this
     */
    public function excluding($excludes)
    {
        $this->filesystem = $this->filesystem->exclude($excludes);

        return $this;
    }

    /**
     * Get all of the files from the given directory (recursive).
     *
     * @param  string $directory
     * @param  bool $hidden
     *
     * @return array
     */
    public function allFiles($directory, $hidden = false)
    {
        return iterator_to_array($this->filesystem->ignoreDotFiles(! $hidden)->in($directory), false);
    }
}
