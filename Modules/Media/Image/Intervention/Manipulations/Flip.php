<?php

namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class Flip implements ImageHandlerInterface
{
    private $defaults = [
        'mode' => 'h',
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

        return $image->flip($options['mode']);
    }
}
