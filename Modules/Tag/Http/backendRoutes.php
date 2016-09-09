<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => 'tag'], function (Router $router) {
    $router->bind('tag__tag', function ($id) {
        return app(\Modules\Tag\Repositories\TagRepository::class)->find($id);
    });
    $router->get('tags', [
        'as' => 'admin.tag.tag.index',
        'uses' => 'TagController@index',
        'middleware' => 'can:tag.tags.index',
    ]);
    $router->get('tags/create', [
        'as' => 'admin.tag.tag.create',
        'uses' => 'TagController@create',
        'middleware' => 'can:tag.tags.create',
    ]);
    $router->post('tags', [
        'as' => 'admin.tag.tag.store',
        'uses' => 'TagController@store',
        'middleware' => 'can:tag.tags.create',
    ]);
    $router->get('tags/{tag__tag}/edit', [
        'as' => 'admin.tag.tag.edit',
        'uses' => 'TagController@edit',
        'middleware' => 'can:tag.tags.edit',
    ]);
    $router->put('tags/{tag__tag}', [
        'as' => 'admin.tag.tag.update',
        'uses' => 'TagController@update',
        'middleware' => 'can:tag.tags.edit',
    ]);
    $router->delete('tags/{tag__tag}', [
        'as' => 'admin.tag.tag.destroy',
        'uses' => 'TagController@destroy',
        'middleware' => 'can:tag.tags.destroy',
    ]);
});
