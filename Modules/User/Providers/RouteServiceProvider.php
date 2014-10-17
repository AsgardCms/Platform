<?php namespace Modules\User\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * Called before routes are registered.
	 *
	 * Register any model bindings or pattern based filters.
	 *
	 * @param  Router  $router
	 * @param  UrlGenerator  $url
	 * @return void
	 */
	public function before(Router $router, UrlGenerator $url)
	{
		$url->setRootControllerNamespace('Modules\User\Http\Controllers');
	}

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map()
	{
		$this->app->booted(function()
		{
			$this->namespaced('Modules\User\Http\Controllers', function(Router $router)
			{
				require __DIR__ . '/../Http/routes.php';
			});
		});
	}
}
