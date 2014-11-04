<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function(Router $router)
{
    $router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Menu\Http\Controllers'], function(Router $router)
    {
        $router->resource('menu', 'Admin\MenuController', ['except' => ['show'], 'names' => [
            'index' => 'dashboard.menu.index',
            'create' => 'dashboard.menu.create',
            'store' => 'dashboard.menu.store',
            'edit' => 'dashboard.menu.edit',
            'update' => 'dashboard.menu.update',
            'destroy' => 'dashboard.menu.destroy',
        ]]);
    });
});

$router->group(['prefix' => 'api', 'namespace' => 'Modules\Menu\Http\Controllers'], function (Router $router) {
    $router->resource('media', 'Api\MenuController', ['only' => ['store']]);
});
