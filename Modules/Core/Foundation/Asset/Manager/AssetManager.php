<?php

namespace Modules\Core\Foundation\Asset\Manager;

interface AssetManager
{
    /**
     * Add a possible asset
     * @param string $dependency
     * @param string $path
     * @return void
     */
    public function addAsset($dependency, $path);

    /**
     * Add an array of possible assets
     * @param array $assets
     * @return void
     */
    public function addAssets(array $assets);

    /**
     * Return all css files to include
     * @return \Illuminate\Support\Collection
     */
    public function allCss();

    /**
     * Return all js files to include
     * @return \Illuminate\Support\Collection
     */
    public function allJs();

    /**
     * @param string $dependency
     * @return string
     */
    public function getJs($dependency);

    /**
     * @param string $dependency
     * @return string
     */
    public function getCss($dependency);
}
