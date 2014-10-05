<?php namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Events\RegisterSidebarMenuItemEvent;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        include __DIR__ . '/../start.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadModuleProviders();
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
     * Load the Service Providers for all enabled modules
     */
    private function loadModuleProviders()
    {
        $this->app->booted(function ($app)
        {
            $modules = $app['modules']->enabled();
            foreach ($modules as $module) {
                if ($providers = $app['modules']->prop("{$module}::providers")) {
                    foreach ($providers as $provider) {
                        $app->register($provider);
                    }
                }
            }
        });
    }
}
