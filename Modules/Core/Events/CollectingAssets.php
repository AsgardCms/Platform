<?php

namespace Modules\Core\Events;

use Illuminate\Support\Str;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;

class CollectingAssets
{
    /**
     * @var AssetPipeline
     */
    private $assetPipeline;

    public function __construct(AssetPipeline $assetPipeline)
    {
        $this->assetPipeline = $assetPipeline;
    }

    /**
     * @param string $asset
     * @return AssetPipeline
     */
    public function requireJs($asset)
    {
        return $this->assetPipeline->requireJs($asset);
    }

    /**
     * @param string $asset
     * @return AssetPipeline
     */
    public function requireCss($asset)
    {
        return $this->assetPipeline->requireCss($asset);
    }

    /**
     * Match a single route
     * @param string|array $route
     * @return bool
     */
    public function onRoute($route)
    {
        $request = request();

        return Str::is($route, $request->route()->getName());
    }

    /**
     * Match multiple routes
     * @param array $routes
     * @return bool
     */
    public function onRoutes(array $routes)
    {
        $request = request();

        foreach ($routes as $route) {
            if (Str::is($route, $request->route()->getName()) === true) {
                return true;
            }
        }

        return false;
    }
}
