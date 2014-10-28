<?php

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function($router)
    {
        $router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Media\Http\Controllers'], function($router)
            {
                $router->resource('media', 'Admin\MediaController', ['only' => ['store'], 'names' => [
                        'store' => 'dashboard.media.store',
                    ]]);
            });
    });

$router->group(['prefix' => 'api', 'namespace' => 'Modules\Media\Http\Controllers'], function ($router) {
        $router->resource('media', 'Api\MediaController');
    });

