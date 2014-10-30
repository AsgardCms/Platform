<?php

if ( ! function_exists('thumbnail'))
{
    /**
     * Getting a thumbnail
     * @param string $path
     * @param string $thumbnail
     * @return
     */
    function thumbnail($path, $thumbnail)
    {
        return Modules\Media\Image\Facade\Imagy::getThumbnail($path, $thumbnail);
    }
}
