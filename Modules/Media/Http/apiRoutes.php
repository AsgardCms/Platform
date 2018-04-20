<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['middleware' => 'api.token'], function (Router $router) {
    $router->get('folder', [
        'uses' => 'AllNestableFolderController',
        'as' => 'api.media.folders.all-nestable',
        'middleware' => 'token-can:media.folders.index',
    ]);
    $router->post('folder', [
        'uses' => 'FolderController@store',
        'as' => 'api.media.folders.store',
        'middleware' => 'token-can:media.folders.create',
    ]);
    $router->get('folder/breadcrumb/{folder}', [
        'uses' => 'FolderBreadcrumbController',
        'as' => 'api.media.folders.breadcrumb',
    ]);
    $router->post('folder/{folder}', [
        'uses' => 'FolderController@update',
        'as' => 'api.media.folders.update',
        'middleware' => 'token-can:media.folders.edit',
    ]);
    $router->delete('folder/{folder}', [
        'uses' => 'FolderController@destroy',
        'as' => 'api.media.folders.destroy',
        'middleware' => 'token-can:media.folders.destroy',
    ]);

    $router->post('file', [
        'uses' => 'MediaController@store',
        'as' => 'api.media.store',
        'middleware' => 'token-can:media.medias.create',
    ]);
    $router->post('file-dropzone', [
        'uses' => 'MediaController@storeDropzone',
        'as' => 'api.media.store-dropzone',
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
    $router->post('media/move', [
        'uses' => 'MoveMediaController',
        'as' => 'api.media.media.move',
    ]);
    $router->get('media/all', [
        'uses' => 'MediaController@all',
        'as' => 'api.media.all',
        'middleware' => 'token-can:media.medias.index',
    ]);
    $router->get('media/all-vue', [
        'uses' => 'MediaController@allVue',
        'as' => 'api.media.all-vue',
        'middleware' => 'token-can:media.medias.index',
    ]);
    $router->post('media/sort', [
        'uses' => 'MediaController@sortMedia',
        'as' => 'api.media.sort',
    ]);
    $router->get('media/find-first-by-zone-and-entity', [
        'uses' => 'MediaController@findFirstByZoneEntity',
        'as' => 'api.media.find-first-by-zone-and-entity',
    ]);

    $router->get('media/get-by-zone-and-entity', [
        'uses' => 'MediaController@getByZoneEntity',
        'as' => 'api.media.get-by-zone-and-entity',
    ]);

    $router->get('media/{media}', [
        'uses' => 'MediaController@find',
        'as' => 'api.media.media.find',
    ]);
    $router->put('file/{file}', [
        'uses' => 'MediaController@update',
        'as' => 'api.media.media.update',
        'middleware' => 'token-can:media.medias.edit',
    ]);
    $router->delete('file/{file}', [
        'uses' => 'MediaController@destroy',
        'as' => 'api.media.media.destroy',
        'middleware' => 'token-can:media.medias.destroy',
    ]);

    $router->post('batch-destroy', [
        'uses' => 'BatchDestroyController',
        'as' => 'api.media.media.batch-destroy',
        'middleware' => 'token-can:media.medias.destroy',
    ]);
});
