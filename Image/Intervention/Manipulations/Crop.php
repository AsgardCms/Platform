<?php namespace Modules\Media\Image\Intervention\Manipulations;

use InvalidArgumentException;
use Modules\Media\Image\ImageHandlerInterface;

class Crop implements ImageHandlerInterface
{
    /**
     * Handle the image manipulation request
     * @param \Intervention\Image\Image $image
     * @param array $options
     * @return mixed
     */
    public function handle($image, $options)
    {
        if (!isset($options['width']) or !isset($options['height'])) {
            throw new InvalidArgumentException('A width and height parameter are required');
        }

        return $image->crop($options['width'], $options['height']);
    }
}
