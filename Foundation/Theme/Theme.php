<?php

namespace Modules\Core\Foundation\Theme;

class Theme
{
    /**
     * @var string the theme name
     */
    private $name;
    /**
     * @var string the theme path
     */
    private $path;

    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = realpath($path);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return strtolower($this->name);
    }
}
