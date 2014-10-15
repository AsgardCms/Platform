<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function() {
	Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Setting\Http\Controllers'], function()
	{
		Route::get('settings', ['as' => 'dashboard.settings','uses' => 'SettingController@index']);
	});
});
