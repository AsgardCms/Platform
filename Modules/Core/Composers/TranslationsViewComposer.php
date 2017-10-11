<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;

class TranslationsViewComposer
{
    public function compose(View $view)
    {
        $staticTranslations = json_encode([
            'page' => array_dot(trans('page::pages')),
            'pages' => array_dot(trans('page::pages')),
            'core' => array_dot(trans('core::core')),
            'media' => array_dot(trans('media::media')),
            'folders' => array_dot(trans('media::folders')),
            'roles' => array_dot(trans('user::roles')),
            'users' => array_dot(trans('user::users')),
            'sidebar' => array_dot(trans('core::sidebar')),
            'dashboard' => array_dot(trans('dashboard::dashboard')),
            'menu' => array_dot(trans('menu::menu')),
            'menu-items' => array_dot(trans('menu::menu-items')),
            'settings' => array_dot(trans('setting::settings')),
            'tags' => array_dot(trans('tag::tags')),
            'translations' => array_dot(trans('translation::translations')),
            'workshop' => array_dot(trans('workshop::workshop')),
            'modules' => array_dot(trans('workshop::modules')),
            'themes' => array_dot(trans('workshop::themes')),
        ]);

        $view->with(compact('staticTranslations'));
    }
}
