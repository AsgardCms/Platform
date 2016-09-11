<?php

namespace Modules\Workshop\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\Composer;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Workshop\Console\EntityScaffoldCommand;
use Modules\Workshop\Console\ModuleScaffoldCommand;
use Modules\Workshop\Console\ThemeScaffoldCommand;
use Modules\Workshop\Console\UpdateModuleCommand;
use Modules\Workshop\Manager\StylistThemeManager;
use Modules\Workshop\Manager\ThemeManager;
use Modules\Workshop\Scaffold\Module\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Module\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\Module\Generators\ValueObjectGenerator;
use Modules\Workshop\Scaffold\Module\ModuleScaffold;
use Modules\Workshop\Scaffold\Theme\ThemeGeneratorFactory;
use Modules\Workshop\Scaffold\Theme\ThemeScaffold;

class WorkshopServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->bindThemeManager();
    }

    public function boot()
    {
        $this->publishConfig('workshop', 'permissions');
        $this->publishConfig('workshop', 'config');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register artisan commands
     */
    private function registerCommands()
    {
        $this->registerModuleScaffoldCommand();
        $this->registerUpdateCommand();
        $this->registerThemeScaffoldCommand();

        $this->commands([
            'command.asgard.module.scaffold',
            'command.asgard.module.update',
            'command.asgard.theme.scaffold',
            EntityScaffoldCommand::class,
        ]);
    }

    /**
     * Register the scaffold command
     */
    private function registerModuleScaffoldCommand()
    {
        $this->app->singleton('asgard.module.scaffold', function ($app) {
            return new ModuleScaffold(
                $app['files'],
                $app['config'],
                new EntityGenerator($app['files'], $app['config']),
                new ValueObjectGenerator($app['files'], $app['config']),
                new FilesGenerator($app['files'], $app['config'])
            );
        });

        $this->app->singleton('command.asgard.module.scaffold', function ($app) {
            return new ModuleScaffoldCommand($app['asgard.module.scaffold']);
        });
    }

    /**
     * Register the update module command
     */
    private function registerUpdateCommand()
    {
        $this->app->singleton('command.asgard.module.update', function ($app) {
            return new UpdateModuleCommand(new Composer($app['files'], base_path()));
        });
    }

    /**
     * Register the theme scaffold command
     */
    private function registerThemeScaffoldCommand()
    {
        $this->app->singleton('asgard.theme.scaffold', function ($app) {
            return new ThemeScaffold(new ThemeGeneratorFactory(), $app['files']);
        });

        $this->app->singleton('command.asgard.theme.scaffold', function ($app) {
            return new ThemeScaffoldCommand($app['asgard.theme.scaffold']);
        });
    }

    /**
     * Bind the theme manager
     */
    private function bindThemeManager()
    {
        $this->app->singleton(ThemeManager::class, function ($app) {
            return new StylistThemeManager($app['files']);
        });
    }
}
