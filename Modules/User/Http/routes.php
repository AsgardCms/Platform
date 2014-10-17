<?php

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function($router)
{
    $router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\User\Http\Controllers'], function($router)
    {
        $router->resource('users', 'Admin\UserController', ['except' => ['show'], 'names' => [
                'index' => 'dashboard.user.index',
                'create' => 'dashboard.user.create',
                'store' => 'dashboard.user.store',
                'edit' => 'dashboard.user.edit',
                'update' => 'dashboard.user.update',
                'destroy' => 'dashboard.user.destroy',
            ]]);
        $router->resource('roles', 'Admin\RolesController', ['except' => ['show'], 'names' => [
            'index' => 'dashboard.role.index',
            'create' => 'dashboard.role.create',
            'store' => 'dashboard.role.store',
            'edit' => 'dashboard.role.edit',
            'update' => 'dashboard.role.update',
            'destroy' => 'dashboard.role.destroy',
        ]]);
    });
});

$router->group(['prefix' => 'auth', 'namespace' => 'Modules\User\Http\Controllers'], function($router)
{
    # Login
    $router->get('login', ['before' => 'auth.guest', 'as' => 'login', 'uses' => 'AuthController@getLogin']);
    $router->post('login', array('as' => 'login.post', 'uses' => 'AuthController@postLogin'));
    # Register
    $router->get('register', ['before' => 'auth.guest', 'as' => 'register', 'uses' => 'AuthController@getRegister']);
    $router->post('register', array('as' => 'register.post', 'uses' => 'AuthController@postRegister'));
    # Account Activation
    $router->get('activate/{userId}/{activationCode}', 'AuthController@getActivate');
    # Reset password
    $router->get('reset', ['as' => 'reset', 'uses' => 'AuthController@getReset']);
    $router->post('reset', ['as' => 'reset.post', 'uses' => 'AuthController@postReset']);
    $router->get('reset/{id}/{code}', ['as' => 'reset.complete', 'uses' => 'AuthController@getResetComplete']);
    $router->post('reset/{id}/{code}', ['as' => 'reset.complete.post', 'uses' => 'AuthController@postResetComplete']);
    # Logout
    $router->get('logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));
});
