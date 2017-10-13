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
        $router->get('all', [
            'as' => 'api.user.role.all',
            'uses' => 'RoleController@all',
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

    $router->group(['prefix' => 'users'], function (Router $router) {
        $router->bind('user', function ($id) {
            return app(\Modules\User\Repositories\UserRepository::class)->find($id);
        });
        $router->get('/', [
            'as' => 'api.user.user.index',
            'uses' => 'UserController@index',
            'middleware' => 'token-can:user.users.index',
        ]);
        $router->post('/', [
            'as' => 'api.user.user.store',
            'uses' => 'UserController@store',
            'middleware' => 'token-can:user.users.create',
        ]);
        $router->post('find/{user}', [
            'as' => 'api.user.user.find',
            'uses' => 'UserController@find',
            'middleware' => 'token-can:user.users.edit',
        ]);
        $router->post('find-new', [
            'as' => 'api.user.user.find-new',
            'uses' => 'UserController@findNew',
            'middleware' => 'token-can:user.users.edit',
        ]);
        $router->post('{user}/edit', [
            'as' => 'api.user.user.update',
            'uses' => 'UserController@update',
            'middleware' => 'token-can:user.users.edit',
        ]);
        $router->get('{user}/sendResetPassword', [
            'as' => 'api.user.user.sendResetPassword',
            'uses' => 'UserController@sendResetPassword',
            'middleware' => 'token-can:user.users.edit',
        ]);
        $router->delete('{user}', [
            'as' => 'api.user.user.destroy',
            'uses' => 'UserController@destroy',
            'middleware' => 'token-can:user.users.destroy',
        ]);
    });

    $router->group(['prefix' => '/account'], function (Router $router) {
        $router->get('profile', [
            'as' => 'api.account.profile.find-current-user',
            'uses' => 'ProfileController@findCurrentUser',
        ]);
        $router->post('profile', [
            'as' => 'api.account.profile.update',
            'uses' => 'ProfileController@update',
        ]);
    });

    $router->get('permissions', [
        'as' => 'api.user.permissions.index',
        'uses' => 'PermissionsController@index',
        'middleware' => 'token-can:user.roles.index',
    ]);
});
