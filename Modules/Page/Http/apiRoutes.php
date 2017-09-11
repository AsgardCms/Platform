<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->bind('page', function ($id) {
    return app(\Modules\Page\Repositories\PageRepository::class)->find($id);
});

$router->group(['prefix' => '/page'], function (Router $router) {
    $router->get('pages', [
        'as' => 'api.page.page.index',
        'uses' => 'PageController@index',
        'middleware' => 'token-can:page.pages.index',
    ]);
    $router->delete('pages/{page}', [
        'as' => 'api.page.page.destroy',
        'uses' => 'PageController@destroy',
        'middleware' => 'token-can:page.pages.destroy',
    ]);
});
