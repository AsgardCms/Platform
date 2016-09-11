<?php

namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class Trim implements ImageHandlerInterface
{
    private $defaults = [
        'base' => 'top-left',
        'away' => ['top', 'bottom', 'left', 'right'],
        'tolerance' => 0,
        'feather' => 0,
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

        return $image->trim($options['base'], $options['away'], $options['tolerance'], $options['feather']);
    }
}
