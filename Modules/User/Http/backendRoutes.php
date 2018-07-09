<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/user'], function (Router $router) {
    $router->get('users', [
        'as' => 'admin.user.user.index',
        'uses' => 'UserController@index',
        'middleware' => 'can:user.users.index',
    ]);
    $router->get('users/create', [
        'as' => 'admin.user.user.create',
        'uses' => 'UserController@create',
        'middleware' => 'can:user.users.create',
    ]);
    $router->post('users', [
        'as' => 'admin.user.user.store',
        'uses' => 'UserController@store',
        'middleware' => 'can:user.users.create',
    ]);
    $router->get('users/{users}/edit', [
        'as' => 'admin.user.user.edit',
        'uses' => 'UserController@edit',
        'middleware' => 'can:user.users.edit',
    ]);
    $router->put('users/{users}/edit', [
        'as' => 'admin.user.user.update',
        'uses' => 'UserController@update',
        'middleware' => 'can:user.users.edit',
    ]);
    $router->get('users/{users}/sendResetPassword', [
        'as' => 'admin.user.user.sendResetPassword',
        'uses' => 'UserController@sendResetPassword',
        'middleware' => 'can:user.users.edit',
    ]);
    $router->delete('users/{users}', [
        'as' => 'admin.user.user.destroy',
        'uses' => 'UserController@destroy',
        'middleware' => 'can:user.users.destroy',
    ]);

    $router->get('roles', [
        'as' => 'admin.user.role.index',
        'uses' => 'RolesController@index',
        'middleware' => 'can:user.roles.index',
    ]);
    $router->get('roles/create', [
        'as' => 'admin.user.role.create',
        'uses' => 'RolesController@create',
        'middleware' => 'can:user.roles.create',
    ]);
    $router->post('roles', [
        'as' => 'admin.user.role.store',
        'uses' => 'RolesController@store',
        'middleware' => 'can:user.roles.create',
    ]);
    $router->get('roles/{roles}/edit', [
        'as' => 'admin.user.role.edit',
        'uses' => 'RolesController@edit',
        'middleware' => 'can:user.roles.edit',
    ]);
    $router->put('roles/{roles}/edit', [
        'as' => 'admin.user.role.update',
        'uses' => 'RolesController@update',
        'middleware' => 'can:user.roles.edit',
    ]);
    $router->delete('roles/{roles}', [
        'as' => 'admin.user.role.destroy',
        'uses' => 'RolesController@destroy',
        'middleware' => 'can:user.roles.destroy',
    ]);
});

$router->group(['prefix' => '/account'], function (Router $router) {
    $router->get('profile', [
        'as' => 'admin.account.profile.edit',
        'uses' => 'Account\ProfileController@edit',
    ]);
    $router->put('profile', [
        'as' => 'admin.account.profile.update',
        'uses' => 'Account\ProfileController@update',
    ]);
    $router->get('api-keys', [
        'as' => 'admin.account.api.index',
        'uses' => 'Account\ApiKeysController@index',
        'middleware' => 'can:account.api-keys.index',
    ]);
    $router->get('api-keys/create', [
        'as' => 'admin.account.api.create',
        'uses' => 'Account\ApiKeysController@create',
        'middleware' => 'can:account.api-keys.create',
    ]);
    $router->delete('api-keys/{userTokenId}', [
        'as' => 'admin.account.api.destroy',
        'uses' => 'Account\ApiKeysController@destroy',
        'middleware' => 'can:account.api-keys.destroy',
    ]);
});
