<?php namespace Modules\Media\Image;

use Illuminate\Contracts\Config\Repository;
use Pingpong\Modules\Repository as Module;

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

    /**
     * Return all thumbnails for all modules
     * @return array
     */
    public function all()
    {
        $thumbnails = [];
        foreach ($this->module->enabled() as $enabledModule) {
            $configuration = $this->config->get(strtolower($enabledModule) . '::thumbnails');
            $thumbnails = array_merge($thumbnails, $configuration);
        }

        return $thumbnails;
    }

    /**
     * Find the filters for the given thumbnail
     * @param $thumbnail
     */
    public function find($thumbnail)
    {
        foreach ($this->all() as $thumbName => $filters) {
            if ($thumbName == $thumbnail) {
                return $filters;
            }
        }
    }

}
