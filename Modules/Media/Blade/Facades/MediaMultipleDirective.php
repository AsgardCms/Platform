<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class MediaMultipleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media.multiple.directive';
    }
}
