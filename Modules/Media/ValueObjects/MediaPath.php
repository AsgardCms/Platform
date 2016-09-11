<?php

namespace Modules\Media\ValueObjects;

use Modules\Media\UrlResolvers\BaseUrlResolver;

class MediaPath
{
    /**
     * @var string
     */
    private $path;

    public function __construct($path)
    {
        if (! is_string($path)) {
            throw new \InvalidArgumentException('The path must be a string');
        }
        $this->path = $path;
    }

    /**
     * Get the URL depending on configured disk
     * @return string
     */
    public function getUrl()
    {
        return (new BaseUrlResolver())->resolve($this->path);
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->path;
    }

    public function __toString()
    {
        try {
            return $this->getUrl();
        } catch (\Exception $e) {
            return '';
        }
    }
}
