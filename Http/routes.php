<?php

Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Core\Http\Controllers'], function()
{
	Route::get('/', 'DashboardController@index');
});