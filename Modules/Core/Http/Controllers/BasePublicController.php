<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;
use Modules\Core\Foundation\Asset\Types\AssetTypeFactory;
use Modules\User\Contracts\Authentication;

abstract class BasePublicController extends Controller
{
    /**
     * @var Authentication
     */
    protected $auth;
    public $locale;

    /**
     * @var array Alternate URLs
     */
    public $alternateUrls = [];

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

    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->auth = app(Authentication::class);

        $this->assetManager = app(AssetManager::class);
        $this->assetPipeline = app(AssetPipeline::class);
        $this->assetFactory = app(AssetTypeFactory::class);

        $this->addAssets();
        $this->requireDefaultAssets();
        view()->share('alternate', $this->alternateUrls);
    }

    /**
     * Add the assets from the config file on the asset manager.
     */
    private function addAssets()
    {
        foreach (config('asgard.core.core.frontend-assets') as $assetName => $path) {
            $path = $this->assetFactory->make($path)->url();
            $this->assetManager->addAsset($assetName, $path);
        }
    }

    /**
     * Require the default assets from config file on the asset pipeline.
     */
    private function requireDefaultAssets()
    {
        $this->assetPipeline->requireCss(config('asgard.core.core.frontend-required-assets.css'));
        $this->assetPipeline->requireJs(config('asgard.core.core.frontend-required-assets.js'));
    }

    /**
     * Add alternate URLs to main array and inject it to the page
     *
     * @param array $alternateUrls
     * @return void
     */
    protected function addAlternateUrls(array $alternateUrls)
    {
        $this->alternateUrls = array_merge($this->alternateUrls, $alternateUrls);
        view()->share('alternate', $this->alternateUrls);
    }
}
