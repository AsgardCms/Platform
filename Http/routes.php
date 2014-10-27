<?php

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function($router)
    {
        $router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Media\Http\Controllers'], function($router)
            {
                $router->resource('media', 'Admin\MediaController', ['except' => ['show'], 'names' => [
                        'index' => 'dashboard.media.index',
                        'create' => 'dashboard.media.create',
                        'store' => 'dashboard.media.store',
                        'edit' => 'dashboard.media.edit',
                        'update' => 'dashboard.media.update',
                        'destroy' => 'dashboard.media.destroy',
                    ]]);
            });
    });
