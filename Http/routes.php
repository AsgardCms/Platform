<?php

Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Dashboard\Http\Controllers'], function()
{
	Route::get('/', 'Admin\DashboardController@index');
});