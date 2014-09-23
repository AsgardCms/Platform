<?php

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
});