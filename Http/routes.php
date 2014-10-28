<?php

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function($router)
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

$router->get('admin/grid-files', 'Modules\Media\Http\Controllers\Admin\MediaController@gridFiles');

$router->group(['prefix' => 'api', 'namespace' => 'Modules\Media\Http\Controllers'], function ($router) {
        $router->resource('media', 'Api\MediaController', ['only' => ['store']]);
    });
