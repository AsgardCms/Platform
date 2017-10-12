<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/user', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    $router->group(['prefix' => 'roles'], function (Router $router) {
        $router->bind('role', function ($id) {
            return app(\Modules\User\Repositories\RoleRepository::class)->find($id);
        });
        $router->get('/', [
            'as' => 'api.user.role.index',
            'uses' => 'RoleController@index',
            'middleware' => 'token-can:user.roles.index',
        ]);
        $router->post('/', [
            'as' => 'api.user.role.store',
            'uses' => 'RoleController@store',
            'middleware' => 'token-can:user.roles.create',
        ]);
        $router->post('find/{role}', [
            'as' => 'api.user.role.find',
            'uses' => 'RoleController@find',
            'middleware' => 'token-can:user.roles.edit',
        ]);
        $router->post('find-new', [
            'as' => 'api.user.role.find-new',
            'uses' => 'RoleController@findNew',
            'middleware' => 'token-can:user.roles.edit',
        ]);
        $router->post('{role}/edit', [
            'as' => 'api.user.role.update',
            'uses' => 'RoleController@update',
            'middleware' => 'token-can:user.roles.edit',
        ]);
        $router->delete('{role}', [
            'as' => 'api.user.role.destroy',
            'uses' => 'RoleController@destroy',
            'middleware' => 'token-can:user.roles.destroy',
        ]);
    });

    $router->get('permissions', [
        'as' => 'api.user.permissions.index',
        'uses' => 'PermissionsController@index',
        'middleware' => 'token-can:user.roles.index',
    ]);
});
