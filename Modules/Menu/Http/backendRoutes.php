<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/menu'], function (Router $router) {
    $router->get('menus', [
        'as' => 'admin.menu.menu.index',
        'uses' => 'MenuController@index',
        'middleware' => 'can:menu.menus.index',
    ]);
    $router->get('menus/create', [
        'as' => 'admin.menu.menu.create',
        'uses' => 'MenuController@create',
        'middleware' => 'can:menu.menus.create',
    ]);
    $router->post('menus', [
        'as' => 'admin.menu.menu.store',
        'uses' => 'MenuController@store',
        'middleware' => 'can:menu.menus.create',
    ]);
    $router->get('menus/{menu}/edit', [
        'as' => 'admin.menu.menu.edit',
        'uses' => 'MenuController@edit',
        'middleware' => 'can:menu.menus.edit',
    ]);
    $router->put('menus/{menu}', [
        'as' => 'admin.menu.menu.update',
        'uses' => 'MenuController@update',
        'middleware' => 'can:menu.menus.edit',
    ]);
    $router->delete('menus/{menu}', [
        'as' => 'admin.menu.menu.destroy',
        'uses' => 'MenuController@destroy',
        'middleware' => 'can:menu.menus.destroy',
    ]);

    $router->get('menus/{menu}/menuitem', [
        'as' => 'dashboard.menuitem.index',
        'uses' => 'MenuItemController@index',
        'middleware' => 'can:menu.menuitems.index',
    ]);
    $router->get('menus/{menu}/menuitem/create', [
        'as' => 'dashboard.menuitem.create',
        'uses' => 'MenuItemController@create',
        'middleware' => 'can:menu.menuitems.create',
    ]);
    $router->post('menus/{menu}/menuitem', [
        'as' => 'dashboard.menuitem.store',
        'uses' => 'MenuItemController@store',
        'middleware' => 'can:menu.menuitems.create',
    ]);
    $router->get('menus/{menu}/menuitem/{menuitem}/edit', [
        'as' => 'dashboard.menuitem.edit',
        'uses' => 'MenuItemController@edit',
        'middleware' => 'can:menu.menuitems.edit',
    ]);
    $router->put('menus/{menu}/menuitem/{menuitem}', [
        'as' => 'dashboard.menuitem.update',
        'uses' => 'MenuItemController@update',
        'middleware' => 'can:menu.menuitems.edit',
    ]);
    $router->delete('menus/{menu}/menuitem/{menuitem}', [
        'as' => 'dashboard.menuitem.destroy',
        'uses' => 'MenuItemController@destroy',
        'middleware' => 'can:menu.menuitems.destroy',
    ]);
});
