<?php

namespace Modules\Media\Image;

class Thumbnail
{
    /**
     * @var array
     */
    private $filters;
    /**
     * @var string
     */
    private $name;

    /**
     * @param $name
     * @param $filters
     */
    private function __construct($name, $filters)
    {
        $this->filters = $filters;
        $this->name = $name;
    }

    /**
     * @param $thumbnailDefinition
     * @return static
     */
    public static function make($thumbnailDefinition)
    {
        $name = key($thumbnailDefinition);

        return new static($name, $thumbnailDefinition[$name]);
    }

    /**
     * Make multiple thumbnail classes with the given array
     * @param array $thumbnailDefinitions
     * @return array
     */
    public static function makeMultiple(array $thumbnailDefinitions)
    {
        $thumbnails = [];

        foreach ($thumbnailDefinitions as $name => $thumbnail) {
            $thumbnails[] = self::make([$name => $thumbnail]);
        }

        return $thumbnails;
    }

    /**
     * Return the thumbnail name
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Return the first width option found in the filters
     * @return int
     */
    public function width()
    {
        return $this->getFirst('width');
    }

    /**
     * Return the first height option found in the filters
     * @return int
     */
    public function height()
    {
        return $this->getFirst('height');
    }

    /**
     * Get the thumbnail size in format: width x height
     * @return string
     */
    public function size()
    {
        return $this->width() . 'x' . $this->height();
    }

    /**
     * Get the first found key in filters
     * @param string $key
     * @return int
     */
    private function getFirst($key)
    {
        foreach ($this->filters as $filter) {
            if (isset($filter[$key])) {
                return (int) $filter[$key];
            }
        }
    }
}
