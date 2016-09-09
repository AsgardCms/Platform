<?php

namespace Modules\Setting\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'setting.settings';
    }
}
