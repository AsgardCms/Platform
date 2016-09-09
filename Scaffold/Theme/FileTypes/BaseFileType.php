<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

abstract class BaseFileType
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var array
     */
    protected $options;

    public function __construct(array $options)
    {
        $this->finder = app('Illuminate\Filesystem\Filesystem');
        $this->options = $options;
    }
}
