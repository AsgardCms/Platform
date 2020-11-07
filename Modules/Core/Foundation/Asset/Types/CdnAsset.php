<?php

namespace Modules\Core\Foundation\Asset\Types;

use Illuminate\Support\Arr;

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
        return Arr::get($this->path, 'cdn');
    }
}
