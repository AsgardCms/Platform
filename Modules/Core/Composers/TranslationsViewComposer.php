<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;

class TranslationsViewComposer
{
    public function compose(View $view)
    {
        $staticTranslations = json_encode([
            'page' => array_dot(trans('page::pages')),
            'core' => array_dot(trans('core::core')),
        ]);

        $view->with(compact('staticTranslations'));
    }
}
