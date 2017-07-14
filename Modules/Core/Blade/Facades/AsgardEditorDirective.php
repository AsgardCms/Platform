<?php

namespace Modules\Core\Blade\Facades;

use Illuminate\Support\Facades\Facade;

class AsgardEditorDirective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'core.asgard.editor';
    }
}
