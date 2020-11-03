<?php

namespace Modules\Core\Foundation\Asset\Types;

use Illuminate\Support\Arr;
use FloatingPoint\Stylist\Facades\ThemeFacade as Theme;

class ThemeAsset implements AssetType
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
        return Theme::url($this->getPath());
    }

    private function getPath()
    {
        return Arr::get($this->path, 'theme');
    }
}
