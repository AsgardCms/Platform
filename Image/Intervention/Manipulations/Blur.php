<?php namespace Modules\Media\Image\Intervention\Manipulations;

use InvalidArgumentException;
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
        if (!isset($options['amount'])) {
            throw new InvalidArgumentException('An amount option is required');
        }

        return $image->blur($options['amount']);
    }
}
