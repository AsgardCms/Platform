<?php namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\InstallCommand;

class CoreServiceProvider extends ServiceProvider
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
            'permissions' => 'PermissionFilter',
            'auth.admin' => 'AdminFilter',
        ]
    ];

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
        $this->app->booted(function ($app) {
            $this->registerFilters($app['router']);
        });
        $this->registerCommands();
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

    /**
     * Register the console commands
     */
    private function registerCommands()
    {
        $this->registerInstallCommand();
    }

    /**
     * Register the installation command
     */
    private function registerInstallCommand()
    {
        $this->app->bindShared('command.platform.install', function($app) {
            return new InstallCommand(
                $app['Modules\User\Repositories\UserRepository'],
                $app['files']
            );
        });

        $this->commands(
            'command.platform.install'
        );
    }
}
