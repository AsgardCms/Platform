<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Events\LoadingBackendTranslations;

class TranslationsViewComposer
{
    public function compose(View $view)
    {
        if (app('asgard.onBackend') === false) {
            return;
        }
        event($staticTranslations = new LoadingBackendTranslations());

        $view->with('staticTranslations', json_encode($staticTranslations->getTranslations()));
    }
}
