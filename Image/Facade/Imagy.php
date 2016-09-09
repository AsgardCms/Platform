<?php

namespace Modules\Media\Image\Facade;

use Illuminate\Support\Facades\Facade;

class Imagy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'imagy';
    }
}
