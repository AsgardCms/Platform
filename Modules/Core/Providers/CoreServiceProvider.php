<?php namespace Modules\Core\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\InstallCommand;
use Modules\Core\Services\Composer;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Repositories\Eloquent\EloquentMenuItemRepository;

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
        $this->registerMenuRoutes();
        $this->registerFilters($this->app['router']);
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
        $this->app->bindShared('command.asgard.install', function($app) {
            return new InstallCommand(
                $app['Modules\User\Repositories\UserRepository'],
                $app['files'],
                $app,
                new Composer($app['files'])
            );
        });

        $this->commands(
            'command.asgard.install'
        );
    }

    private function registerMenuRoutes()
    {
        $this->app->bind(
            'Modules\Menu\Repositories\MenuItemRepository',
            function() {
                return new EloquentMenuItemRepository(new Menuitem);
            }
        );
        $this->app->singleton('Asgard.routes', function (Application $app) {
            return $app->make('Modules\Menu\Repositories\MenuItemRepository')->getForRoutes();
        });
    }
}
