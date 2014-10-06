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
