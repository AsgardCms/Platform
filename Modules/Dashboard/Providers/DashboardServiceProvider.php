<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Dashboard\Entities\Widget;
use Modules\Dashboard\Events\Handlers\RegisterDashboardSidebar;
use Modules\Dashboard\Repositories\Cache\CacheWidgetDecorator;
use Modules\Dashboard\Repositories\Eloquent\EloquentWidgetRepository;
use Modules\Dashboard\Repositories\WidgetRepository;
use Modules\Workshop\Manager\StylistThemeManager;

class DashboardServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
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
        $this->app->bind(WidgetRepository::class, function () {
            $repository = new EloquentWidgetRepository(new Widget());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheWidgetDecorator($repository);
        });

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('dashboard', RegisterDashboardSidebar::class)
        );

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('dashboard', array_dot(trans('dashboard::dashboard')));
        });
    }

    public function boot(StylistThemeManager $theme)
    {
        $this->publishes([
            __DIR__ . '/../Resources/views' => base_path('resources/views/asgard/dashboard'),
        ], 'views');

        $this->app['view']->prependNamespace(
            'dashboard',
            $theme->find(config('asgard.core.core.admin-theme'))->getPath() . '/views/modules/dashboard'
        );

        $this->publishConfig('dashboard', 'permissions');
        $this->publishConfig('dashboard', 'config');
        $this->publishConfig('dashboard', 'settings');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
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
}
