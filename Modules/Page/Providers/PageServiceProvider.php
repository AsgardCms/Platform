<?php

namespace Modules\Page\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\CollectingAssets;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Page\Console\CreatePagesCommand;
use Modules\Page\Entities\Page;
use Modules\Page\Events\Handlers\RegisterPageSidebar;
use Modules\Page\Repositories\Cache\CachePageDecorator;
use Modules\Page\Repositories\Eloquent\EloquentPageRepository;
use Modules\Page\Repositories\PageRepository;
use Modules\Page\Services\FinderService;
use Modules\Tag\Repositories\TagManager;

class PageServiceProvider extends ServiceProvider
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
        $this->registerBindings();

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('page', RegisterPageSidebar::class)
        );

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('pages', array_dot(trans('page::pages')));
        });

        app('router')->bind('page', function ($id) {
            return app(PageRepository::class)->find($id);
        });
    }

    public function boot()
    {
        $this->publishConfig('page', 'config');
        $this->publishConfig('page', 'permissions');

        $this->app[TagManager::class]->registerNamespace(new Page());
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->handleAssets();

        $this->commands(CreatePagesCommand::class);
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

    private function registerBindings()
    {
        $this->app->bind(FinderService::class, function () {
            return new FinderService();
        });

        $this->app->bind(PageRepository::class, function () {
            $repository = new EloquentPageRepository(new Page());

            if (! Config::get('app.cache')) {
                return $repository;
            }

            return new CachePageDecorator($repository);
        });
    }

    /**
     * Require iCheck on edit and create pages
     */
    private function handleAssets()
    {
        $this->app['events']->listen(CollectingAssets::class, function (CollectingAssets $event) {
            if ($event->onRoutes(['*page*create', '*page*edit'])) {
                $event->requireCss('icheck.blue.css');
                $event->requireJs('icheck.js');
            }
        });
    }
}
