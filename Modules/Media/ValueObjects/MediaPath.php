<?php

namespace Modules\Media\ValueObjects;

use Illuminate\Support\Facades\Storage;

class MediaPath
{
    /**
     * @var string
     */
    private $path;

    public function __construct($path)
    {
        if (! is_string($path)) {
            throw new \InvalidArgumentException('The path must be a string');
        }
        $this->path = $path;
    }

    /**
     * Get the URL depending on configured disk
     * @return string
     */
    public function getUrl()
    {
        $path = ltrim($this->path, '/');

        return Storage::disk(config('asgard.media.config.filesystem'))->url($path);
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->path;
    }

    public function __toString()
    {
        try {
            return $this->getUrl();
        } catch (\Exception $e) {
            return '';
        }
    }
}
