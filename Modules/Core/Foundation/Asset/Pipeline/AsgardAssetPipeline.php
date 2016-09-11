<?php

namespace Modules\Core\Foundation\Asset\Pipeline;

use Illuminate\Support\Collection;
use Modules\Core\Foundation\Asset\AssetNotFoundException;
use Modules\Core\Foundation\Asset\Manager\AssetManager;

class AsgardAssetPipeline implements AssetPipeline
{
    /**
     * @var Collection
     */
    protected $css;
    /**
     * @var Collection
     */
    protected $js;

    public function __construct(AssetManager $assetManager)
    {
        $this->css = new Collection();
        $this->js = new Collection();
        $this->assetManager = $assetManager;
    }

    /**
     * Add a javascript dependency on the view
     * @param string $dependency
     * @return $this
     * @throws AssetNotFoundException
     */
    public function requireJs($dependency)
    {
        if (is_array($dependency)) {
            foreach ($dependency as $dependency) {
                $this->requireJs($dependency);
            }
        }

        $assetPath = $this->assetManager->getJs($dependency);

        $this->guardForAssetNotFound($assetPath);

        $this->js->put($dependency, $assetPath);

        return $this;
    }

    /**
     * Add a CSS dependency on the view
     * @param string $dependency
     * @return $this
     * @throws AssetNotFoundException
     */
    public function requireCss($dependency)
    {
        if (is_array($dependency)) {
            foreach ($dependency as $dependency) {
                $this->requireCss($dependency);
            }
        }

        $assetPath = $this->assetManager->getCss($dependency);

        $this->guardForAssetNotFound($assetPath);

        $this->css->put($dependency, $assetPath);

        return $this;
    }

    /**
     * Add the dependency after another one
     * @param string $dependency
     * @return void
     */
    public function after($dependency)
    {
        $this->insert($dependency, 'after');
    }

    /**
     * Add the dependency before another one
     * @param string $dependency
     * @return void
     */
    public function before($dependency)
    {
        $this->insert($dependency, 'before');
    }

    /**
     * Insert a dependency before or after in the right dependency array
     * @param string $dependency
     * @param string $offset
     */
    private function insert($dependency, $offset = 'before')
    {
        $offset = $offset == 'before' ? 0 : 1;

        list($dependencyArray, $collectionName) = $this->findDependenciesForKey($dependency);
        list($key, $value) = $this->getLastKeyAndValueOf($dependencyArray);

        $pos = $this->getPositionInArray($dependency, $dependencyArray);

        $dependencyArray = array_merge(
            array_slice($dependencyArray, 0, $pos + $offset, true),
            [$key => $value],
            array_slice($dependencyArray, $pos, count($dependencyArray) - 1, true)
        );

        $this->$collectionName = new Collection($dependencyArray);
    }

    /**
     * Return all css files to include
     * @return \Illuminate\Support\Collection
     */
    public function allCss()
    {
        return $this->css;
    }

    /**
     * Return all js files to include
     * @return \Illuminate\Support\Collection
     */
    public function allJs()
    {
        return $this->js;
    }

    /**
     * Find in which collection the given dependency exists
     * @param string $dependency
     * @return array
     */
    private function findDependenciesForKey($dependency)
    {
        if ($this->css->get($dependency)) {
            return [$this->css->toArray(), 'css'];
        }

        return [$this->js->toArray(), 'js'];
    }

    /**
     * Get the last key and value the given array
     * @param array $dependencyArray
     * @return array
     */
    private function getLastKeyAndValueOf(array $dependencyArray)
    {
        $value = end($dependencyArray);
        $key = key($dependencyArray);
        reset($dependencyArray);

        return [$key, $value];
    }

    /**
     * Return the position in the array of the given key
     *
     * @param $dependency
     * @param array $dependencyArray
     * @return int
     */
    private function getPositionInArray($dependency, array $dependencyArray)
    {
        $pos = array_search($dependency, array_keys($dependencyArray));

        return $pos;
    }

    /**
     * If asset was not found, throw an exception
     * @param string $assetPath
     * @throws AssetNotFoundException
     */
    private function guardForAssetNotFound($assetPath)
    {
        if (is_null($assetPath)) {
            throw new AssetNotFoundException($assetPath);
        }
    }
}
