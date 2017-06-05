<?php

namespace Modules\Workshop\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;

class ModulesController extends Controller
{
    public function publishAssets($moduleName)
    {
        try {
            Artisan::call('module:publish', ['module' => $moduleName]);
        } catch (InvalidArgumentException $e) {
        }
    }
}
