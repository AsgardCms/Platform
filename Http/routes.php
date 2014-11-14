<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin'], function(Router $router)
{
	$router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Dashboard\Http\Controllers'], function(Router $router)
	{
		$router->get('/', ['as' => 'dashboard.index', 'uses' => 'Admin\DashboardController@index']);
	});
});
