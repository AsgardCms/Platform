<?php

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function($router) {
	$router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Setting\Http\Controllers'], function($router)
	{
		$router->resource('settings', 'Admin\SettingController', ['except' => ['show', 'edit', 'update', 'destroy'], 'names' => [
			'index' => 'dashboard.setting.index',
			'store' => 'dashboard.setting.store'
		]]);
	});
});
