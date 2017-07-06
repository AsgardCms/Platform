<?php

namespace Modules\Media\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class MediaThumbnailDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media.thumbnail.directive';
    }
}
