<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function() {
	Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Setting\Http\Controllers'], function()
	{
		Route::resource('settings', 'Admin\SettingController', ['except' => ['show'], 'names' => [
			'index' => 'dashboard.setting.index',
			'create' => 'dashboard.setting.create',
			'store' => 'dashboard.setting.store',
			'edit' => 'dashboard.setting.edit',
			'update' => 'dashboard.setting.update',
			'destroy' => 'dashboard.setting.destroy',
		]]);
	});
});
