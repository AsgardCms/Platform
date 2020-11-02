<?php

namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class ResizeCanvas implements ImageHandlerInterface
{
    private $defaults = [
        'width' => 200,
        'height' => 200,
    ];

    /**
     * Handle the image manipulation request
     * @param  \Intervention\Image\Image $image
     * @param  array                     $options
     * @return \Intervention\Image\Image
     */
    public function handle($image, $options)
    {
        $options = array_merge($this->defaults, $options);

        $callback = isset($options['callback']) ? $options['callback'] : null;

        return $image->resizeCanvas($options['width'], $options['height'], $callback);
    }
}
