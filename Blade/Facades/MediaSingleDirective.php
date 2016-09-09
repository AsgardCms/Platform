<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class MediaSingleDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media.single.directive';
    }
}
