<?php

Route::group(['prefix' => Config::get('core::config.admin-prefix'), 'namespace' => 'Modules\Dashboard\Http\Controllers'], function()
{
	Route::get('/', ['as' => 'dashboard.index', 'uses' => 'Admin\DashboardController@index']);
});
