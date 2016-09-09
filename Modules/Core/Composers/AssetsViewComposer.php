<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;
use Modules\Core\Foundation\Asset\Types\AssetTypeFactory;

class AssetsViewComposer
{
    /**
     * @var AssetManager
     */
    protected $assetManager;
    /**
     * @var AssetPipeline
     */
    protected $assetPipeline;
    /**
     * @var AssetTypeFactory
     */
    protected $assetFactory;
    /**
     * @var Request
     */
    private $request;

    public function __construct(AssetManager $assetManager, AssetPipeline $assetPipeline, AssetTypeFactory $assetTypeFactory, Request $request)
    {
        $this->assetManager = $assetManager;
        $this->assetPipeline = $assetPipeline;
        $this->assetFactory = $assetTypeFactory;
        $this->request = $request;
    }

    public function compose(View $view)
    {
        if ($this->onBackend() === false) {
            return;
        }

        $view->with('cssFiles', $this->assetPipeline->allCss());
        $view->with('jsFiles', $this->assetPipeline->allJs());
    }

    private function onBackend()
    {
        $url = $this->request->url();
        if (str_contains($url, config('asgard.core.core.admin-prefix'))) {
            return true;
        }

        return false;
    }
}
