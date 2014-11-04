<?php

use Illuminate\Routing\Router;

$router->model('menus', 'Modules\Menu\Entities\Menu');
$router->model('menuitem', 'Modules\Menu\Entities\Menuitem');

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

        $router->resource('menus.menuitem', 'Admin\MenuItemController', ['except' => ['show'], 'names' => [
            'index' => 'dashboard.menuitem.index',
            'create' => 'dashboard.menuitem.create',
            'store' => 'dashboard.menuitem.store',
            'edit' => 'dashboard.menuitem.edit',
            'update' => 'dashboard.menuitem.update',
            'destroy' => 'dashboard.menuitem.destroy',
        ]]);
    });
});

$router->group(['prefix' => 'api', 'namespace' => 'Modules\Menu\Http\Controllers'], function (Router $router) {
    $router->resource('media', 'Api\MenuController', ['only' => ['store']]);
});
