<?php

namespace Modules\Page\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Page\Entities\Page;
use Modules\Page\Repositories\Cache\CachePageDecorator;
use Modules\Page\Repositories\Eloquent\EloquentPageRepository;
use Modules\Page\Services\FinderService;
use Modules\Tag\Repositories\TagManager;

class PageServiceProvider extends ServiceProvider
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
        $this->registerBindings();
    }

    public function boot()
    {
        $this->publishConfig('page', 'config');
        $this->publishConfig('page', 'permissions');

        $this->app[TagManager::class]->registerNamespace(new Page());
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
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

        $this->app->bind(
            'Modules\Page\Repositories\PageRepository',
            function () {
                $repository = new EloquentPageRepository(new Page());

                if (! Config::get('app.cache')) {
                    return $repository;
                }

                return new CachePageDecorator($repository);
            }
        );
    }
}
