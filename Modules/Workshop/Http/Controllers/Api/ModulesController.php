<?php

namespace Modules\Workshop\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Nwidart\Modules\Module;

class ModulesController extends Controller
{
    public function publishAssets(Module $module)
    {
        try {
            Artisan::call('module:publish', ['module' => $module->getName()]);
        } catch (InvalidArgumentException $e) {
        }
    }
}
