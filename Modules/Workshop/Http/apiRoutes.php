<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => 'workshop', 'middleware' => 'api.token'], function (Router $router) {
    $router->post('modules/{module}/publish', [
        'as' => 'api.workshop.module.publish',
        'uses' => 'ModulesController@publishAssets',
        'middleware' => 'token-can:workshop.modules.publish',
    ]);
    $router->post('themes/{theme}/publish', [
        'as' => 'api.workshop.theme.publish',
        'uses' => 'ThemeController@publishAssets',
        'middleware' => 'token-can:workshop.themes.publish',
    ]);
});
