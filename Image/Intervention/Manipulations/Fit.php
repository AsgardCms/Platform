<?php namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class Fit implements ImageHandlerInterface
{
    private $defaults = [
        'width' => 100,
        'height' => null,
        'position' => 'center'
    ];

    /**
     * Handle the image manipulation request
     * @param \Intervention\Image\Image $image
     * @param array $options
     * @return \Intervention\Image\Image
     */
    public function handle($image, $options)
    {
        $options = array_merge($this->defaults, $options);

        return $image->fit($options['width'], $options['height'], null, $options['position']);
    }
}
