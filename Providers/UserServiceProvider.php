<?php namespace Modules\User\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The filters base class name.
     *
     * @var array
     */
    protected $filters = [
        'Core' => [
            'permissions' => 'PermissionFilter'
        ],
        'User' => [
            'auth.guest' => 'GuestFilter'
        ]
    ];
    protected $middleware = [
        'User' => [
            'auth.guest' => 'GuestFilter'
        ]
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function ($app) {
			$this->registerFilters($app['router']);
			$this->registerMiddleware($app['router']);
			$this->registerBindings();
		});
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerFilters(Router $router)
    {
        foreach ($this->filters as $module => $filters) {
            foreach ($filters as $name => $filter) {
                $class = "Modules\\{$module}\\Http\\Filters\\{$filter}";

                $router->filter($name, $class);
            }
        }
    }

	private function registerBindings()
	{
		$this->app->bind(
			'Modules\User\Repositories\UserRepository',
			'Modules\User\Repositories\Sentinel\SentinelUserRepository'
		);
		$this->app->bind(
			'Modules\User\Repositories\RoleRepository',
			'Modules\User\Repositories\Sentinel\SentinelRoleRepository'
		);
        $this->app->bind(
            'Modules\Core\Contracts\Authentication',
            'Modules\User\Repositories\Sentinel\SentinelAuthentication'
        );
	}

    private function registerMiddleware($router)
    {
        foreach ($this->middleware as $module => $middlewares) {
            foreach ($middlewares as $name => $middleware) {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";

                $router->middleware($name, $class);
            }
        }
    }
}
