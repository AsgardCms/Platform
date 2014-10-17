<?php

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function($router) {
	$router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Setting\Http\Controllers'], function($router)
	{
		$router->resource('settings', 'Admin\SettingController', ['except' => ['show'], 'names' => [
			'index' => 'dashboard.setting.index',
			'create' => 'dashboard.setting.create',
			'store' => 'dashboard.setting.store',
			'edit' => 'dashboard.setting.edit',
			'update' => 'dashboard.setting.update',
			'destroy' => 'dashboard.setting.destroy',
		]]);
	});
});
