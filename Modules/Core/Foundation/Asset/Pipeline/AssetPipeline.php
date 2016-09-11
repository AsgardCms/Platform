<?php

namespace Modules\Core\Foundation\Asset\Pipeline;

interface AssetPipeline
{
    /**
     * Add a javascript dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireJs($dependency);

    /**
     * Add a CSS dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireCss($dependency);

    /**
     * Add the dependency after another one
     * @param string $dependency
     * @return void
     */
    public function after($dependency);

    /**
     * Add the dependency before another one
     * @param string $dependency
     * @return void
     */
    public function before($dependency);

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
}
