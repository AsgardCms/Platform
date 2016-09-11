<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => 'auth'], function (Router $router) {
    # Login
    $router->get('login', ['middleware' => 'auth.guest', 'as' => 'login', 'uses' => 'AuthController@getLogin']);
    $router->post('login', ['as' => 'login.post', 'uses' => 'AuthController@postLogin']);
    # Register
    if (config('asgard.user.config.allow_user_registration', true)) {
        $router->get('register', ['middleware' => 'auth.guest', 'as' => 'register', 'uses' => 'AuthController@getRegister']);
        $router->post('register', ['as' => 'register.post', 'uses' => 'AuthController@postRegister']);
    }
    # Account Activation
    $router->get('activate/{userId}/{activationCode}', 'AuthController@getActivate');
    # Reset password
    $router->get('reset', ['as' => 'reset', 'uses' => 'AuthController@getReset']);
    $router->post('reset', ['as' => 'reset.post', 'uses' => 'AuthController@postReset']);
    $router->get('reset/{id}/{code}', ['as' => 'reset.complete', 'uses' => 'AuthController@getResetComplete']);
    $router->post('reset/{id}/{code}', ['as' => 'reset.complete.post', 'uses' => 'AuthController@postResetComplete']);
    # Logout
    $router->get('logout', ['as' => 'logout', 'uses' => 'AuthController@getLogout']);
});
