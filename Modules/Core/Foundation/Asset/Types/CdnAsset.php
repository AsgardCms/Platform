<?php

namespace Modules\Core\Foundation\Asset\Types;

class CdnAsset implements AssetType
{
    /**
     * @var
     */
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Get the URL
     * @return string
     */
    public function url()
    {
        return array_get($this->path, 'cdn');
    }
}
