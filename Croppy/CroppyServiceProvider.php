<?php namespace Modules\Media\Croppy;

use Illuminate\Support\ServiceProvider;

class CroppyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->package('nwidart/croppy');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app['croppy'] = $app->share(function ($app) {
            new Croppy($app['filesystem.disk'], $app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['croppy'];
    }
}
