<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/page'], function (Router $router) {
    $router->get('pages', [
        'as' => 'admin.page.page.index',
        'uses' => 'PageController@index',
        'middleware' => 'can:page.pages.index',
    ]);
    $router->get('pages/create', [
        'as' => 'admin.page.page.create',
        'uses' => 'PageController@create',
        'middleware' => 'can:page.pages.create',
    ]);
    $router->post('pages', [
        'as' => 'admin.page.page.store',
        'uses' => 'PageController@store',
        'middleware' => 'can:page.pages.create',
    ]);
    $router->get('pages/{page}/edit', [
        'as' => 'admin.page.page.edit',
        'uses' => 'PageController@edit',
        'middleware' => 'can:page.pages.edit',
    ]);
    $router->put('pages/{page}/edit', [
        'as' => 'admin.page.page.update',
        'uses' => 'PageController@update',
        'middleware' => 'can:page.pages.edit',
    ]);
    $router->delete('pages/{page}', [
        'as' => 'admin.page.page.destroy',
        'uses' => 'PageController@destroy',
        'middleware' => 'can:page.pages.destroy',
    ]);
});
