<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class RoutingServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * @return string
     */
    abstract protected function getFrontendRoute();

    /**
     * @return string
     */
    abstract protected function getBackendRoute();

    /**
     * @return string
     */
    abstract protected function getApiRoute();

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            $this->loadApiRoutes($router);
        });

        $router->group([
            'namespace' => $this->namespace,
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localizationRedirect', 'web'],
        ], function (Router $router) {
            $this->loadBackendRoutes($router);
            $this->loadFrontendRoutes($router);
        });
    }

    /**
     * @param Router $router
     */
    private function loadFrontendRoutes(Router $router)
    {
        $frontend = $this->getFrontendRoute();

        if ($frontend && file_exists($frontend)) {
            $router->group([
                'middleware' => config('asgard.core.core.middleware.frontend', []),
            ], function (Router $router) use ($frontend) {
                require $frontend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadBackendRoutes(Router $router)
    {
        $backend = $this->getBackendRoute();

        if ($backend && file_exists($backend)) {
            $router->group([
                'namespace' => 'Admin',
                'prefix' => config('asgard.core.core.admin-prefix'),
                'middleware' => config('asgard.core.core.middleware.backend', []),
            ], function (Router $router) use ($backend) {
                require $backend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadApiRoutes(Router $router)
    {
        $api = $this->getApiRoute();

        if ($api && file_exists($api)) {
            $router->group([
                'namespace' => 'Api',
                'prefix' => 'api',
                'middleware' => config('asgard.core.core.middleware.api', []),
            ], function (Router $router) use ($api) {
                require $api;
            });
        }
    }
}
