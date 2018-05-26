<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/media'], function (Router $router) {
    $router->get('media', [
        'as' => 'admin.media.media.index',
        'uses' => 'MediaController@index',
        'middleware' => 'can:media.medias.index',
    ]);
    $router->get('media/create', [
        'as' => 'admin.media.media.create',
        'uses' => 'MediaController@create',
        'middleware' => 'can:media.medias.create',
    ]);
    $router->post('media', [
        'as' => 'admin.media.media.store',
        'uses' => 'MediaController@store',
        'middleware' => 'can:media.medias.create',
    ]);
    $router->get('media/{media}/edit', [
        'as' => 'admin.media.media.edit',
        'uses' => 'MediaController@edit',
        'middleware' => 'can:media.medias.edit',
    ]);
    $router->put('media/{media}', [
        'as' => 'admin.media.media.update',
        'uses' => 'MediaController@update',
        'middleware' => 'can:media.medias.edit',
    ]);
    $router->delete('media/{media}', [
        'as' => 'admin.media.media.destroy',
        'uses' => 'MediaController@destroy',
        'middleware' => 'can:media.medias.destroy',
    ]);

    $router->get('media-grid/index', [
        'uses' => 'MediaGridController@index',
        'as' => 'media.grid.select',
        'middleware' => 'can:media.medias.index',
    ]);
    $router->get('media-grid/ckIndex', [
        'uses' => 'MediaGridController@ckIndex',
        'as' => 'media.grid.ckeditor',
        'middleware' => 'can:media.medias.index',
    ]);
});
