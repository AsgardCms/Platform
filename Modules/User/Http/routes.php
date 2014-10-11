<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function()
{
    Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\User\Http\Controllers'], function()
    {
        Route::resource('users', 'Admin\UserController', ['except' => ['show'], 'names' => [
                'index' => 'dashboard.user.index',
                'create' => 'dashboard.user.create',
                'store' => 'dashboard.user.store',
                'edit' => 'dashboard.user.edit',
                'update' => 'dashboard.user.update',
                'destroy' => 'dashboard.user.destroy',
            ]]);
        Route::resource('roles', 'Admin\RolesController', ['except' => ['show'], 'names' => [
            'index' => 'dashboard.role.index',
            'create' => 'dashboard.role.create',
            'store' => 'dashboard.role.store',
            'edit' => 'dashboard.role.edit',
            'update' => 'dashboard.role.update',
            'destroy' => 'dashboard.role.destroy',
        ]]);
    });
});

Route::group(['prefix' => 'auth', 'namespace' => 'Modules\User\Http\Controllers'], function()
{
    # Login
    Route::get('login', ['before' => 'auth.guest', 'as' => 'login', 'uses' => 'AuthController@getLogin']);
    Route::post('login', array('as' => 'login.post', 'uses' => 'AuthController@postLogin'));
    # Register
    Route::get('register', ['before' => 'auth.guest', 'as' => 'register', 'uses' => 'AuthController@getRegister']);
    Route::post('register', array('as' => 'register.post', 'uses' => 'AuthController@postRegister'));
    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate');
    # Reset password
    Route::get('reset', ['as' => 'reset', 'uses' => 'AuthController@getReset']);
    Route::post('reset', ['as' => 'reset.post', 'uses' => 'AuthController@postReset']);
    Route::get('reset/{id}/{code}', ['as' => 'reset.complete', 'uses' => 'AuthController@getResetComplete']);
    Route::post('reset/{id}/{code}', ['as' => 'reset.complete.post', 'uses' => 'AuthController@postResetComplete']);
    # Logout
    Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));
});
