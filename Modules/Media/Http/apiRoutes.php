<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['middleware' => 'api.token'], function (Router $router) {
    $router->post('file', [
        'uses' => 'MediaController@store',
        'as' => 'api.media.store',
        'middleware' => 'token-can:media.medias.create',
    ]);
    $router->post('media/link', [
        'uses' => 'MediaController@linkMedia',
        'as' => 'api.media.link',
    ]);
    $router->post('media/unlink', [
        'uses' => 'MediaController@unlinkMedia',
        'as' => 'api.media.unlink',
    ]);
    $router->get('media/all', [
        'uses' => 'MediaController@all',
        'as' => 'api.media.all',
        'middleware' => 'token-can:media.medias.index',
    ]);
    $router->post('media/sort', [
        'uses' => 'MediaController@sortMedia',
        'as' => 'api.media.sort',
    ]);
});
