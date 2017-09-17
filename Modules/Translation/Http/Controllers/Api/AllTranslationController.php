<?php

namespace Modules\Translation\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class AllTranslationController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'page' => trans('page::pages'),
            'core' => trans('core::core'),
        ]);
    }
}
