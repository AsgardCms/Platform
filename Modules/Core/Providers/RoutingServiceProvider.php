<?php namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RoutingServiceProvider extends ServiceProvider
{
    public function before(Router $router)
    {
//        $modules = app('modules');
//        $routes = app('Asgard.routes');
//        foreach ($modules->enabled() as $module) {
//            $router->group(
//                ['namespace' => "Modules\\$module\\Http\\Controllers"],
//                function (Router $router) use ($module, $routes) {
//                    foreach (LaravelLocalization::getSupportedLocales() as $locale => $language) {
//                        if ($this->moduleHasRoute($routes, $module, $locale)) {
//                            $uri = $routes[strtolower($module)][$locale];
//                            $router->get(
//                                $uri,
//                                [
//                                    'as' => "{$locale}.{$module}",
//                                    'uses' => 'PublicController@index'
//                                ]
//                            );
//                            $router->get(
//                                $uri . '/{slug}',
//                                [
//                                    'as' => "{$locale}.{$module}.slug",
//                                    'uses' => 'PublicController@show'
//                                ]
//                            );
//                        }
//                    }
//                }
//            );
//        }
    }

    /**
     * @param $routes
     * @param $module
     * @param $locale
     * @return bool
     */
    private function moduleHasRoute($routes, $module, $locale)
    {
        return isset($routes[strtolower($module)][$locale]);
    }

    public function map(Router $router)
    {
    }
}
