<?php

Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Dashboard\Http\Controllers'], function()
{
	Route::get('/', ['as' => 'dashboard.index', 'uses' => 'Admin\DashboardController@index']);
});
