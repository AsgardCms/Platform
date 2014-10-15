<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function() {
	Route::group(['prefix' => 'setting', 'namespace' => 'Modules\Setting\Http\Controllers'], function()
	{
		Route::get('/', ['as' => 'dashboard.settings','uses' => 'SettingController@index']);
	});
});
