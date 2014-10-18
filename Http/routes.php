<?php
$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function($router)
{
	$router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Dashboard\Http\Controllers'], function($router)
	{
		$router->get('/', ['as' => 'dashboard.index', 'uses' => 'Admin\DashboardController@index']);
	});
});
