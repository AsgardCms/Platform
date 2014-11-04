<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function(Router $router)
{
    $router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Menu\Http\Controllers'], function(Router $router)
    {
        $router->resource('menus', 'Admin\MenuController', ['except' => ['show'], 'names' => [
            'index' => 'dashboard.menu.index',
            'create' => 'dashboard.menu.create',
            'store' => 'dashboard.menu.store',
            'edit' => 'dashboard.menu.edit',
            'update' => 'dashboard.menu.update',
            'destroy' => 'dashboard.menu.destroy',
        ]]);

        $router->resource('menus.menulinks', 'Admin\MenuLinkController', ['except' => ['show'], 'names' => [
            'index' => 'dashboard.menulink.index',
            'create' => 'dashboard.menulink.create',
            'store' => 'dashboard.menulink.store',
            'edit' => 'dashboard.menulink.edit',
            'update' => 'dashboard.menulink.update',
            'destroy' => 'dashboard.menulink.destroy',
        ]]);
    });
});

$router->group(['prefix' => 'api', 'namespace' => 'Modules\Menu\Http\Controllers'], function (Router $router) {
    $router->resource('media', 'Api\MenuController', ['only' => ['store']]);
});
