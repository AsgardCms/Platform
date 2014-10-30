<?php namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class Blur implements ImageHandlerInterface
{
    /**
     * Handle the image manipulation request
     * @param \Intervention\Image\Image $image
     * @param array $options
     * @return \Intervention\Image\Image
     */
    public function handle($image, $options)
    {
        return $image->blur($options['amount']);
    }
}
