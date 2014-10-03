<?php
$locale = LaravelLocalization::setLocale();
Route::group(['prefix' => $locale . '/' . Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Dashboard\Http\Controllers'], function()
{
	Route::get('/', ['as' => 'dashboard.index', 'uses' => 'Admin\DashboardController@index']);
});
