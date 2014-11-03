<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'menu', 'namespace' => 'Modules\Menu\Http\Controllers'], function (Router $router) {
    $router->get('/', 'MenuController@index');
});
