<?php namespace Modules\Media\Image;

use Illuminate\Contracts\Config\Repository;
use Pingpong\Modules\Module;

class ThumbnailsManager
{
    /**
     * @var Module
     */
    private $module;
    /**
     * @var Repository
     */
    private $config;

    /**
     * @param Repository $config
     * @param Module $module
     */
    public function __construct(Repository $config, Module $module)
    {
        $this->module = $module;
        $this->config = $config;
    }

    public function all()
    {
        $thumbnails = [];
        foreach ($this->module->enabled() as $enabledModule) {
            $configuration = $this->config->get(strtolower($enabledModule) . '::thumbnails');
            $thumbnails = array_merge($thumbnails, $configuration);
        }

        return $thumbnails;
    }
}
