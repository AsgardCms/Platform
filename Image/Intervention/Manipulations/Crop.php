<?php namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class Crop extends BaseManipulation implements ImageHandlerInterface
{
    /**
     * Handle the image manipulation request
     * @param \Intervention\Image\Image $image
     * @param array $options
     * @return mixed
     */
    public function handle($image, $options)
    {
        return $image->crop($options['width'], $options['height']);
    }
}
