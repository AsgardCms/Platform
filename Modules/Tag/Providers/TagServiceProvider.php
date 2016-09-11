<?php

namespace Modules\Tag\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Tag\Blade\TagWidget;
use Modules\Tag\Entities\Tag;
use Modules\Tag\Repositories\Cache\CacheTagDecorator;
use Modules\Tag\Repositories\Eloquent\EloquentTagRepository;
use Modules\Tag\Repositories\TagManager;
use Modules\Tag\Repositories\TagManagerRepository;
use Modules\Tag\Repositories\TagRepository;

class TagServiceProvider extends ServiceProvider
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

        $this->app->singleton('tag.widget.directive', function ($app) {
            return new TagWidget($app[TagRepository::class]);
        });
    }

    public function boot()
    {
        $this->publishConfig('tag', 'permissions');
        $this->publishConfig('tag', 'config');
        $this->registerBladeTags();
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
        $this->app->bind(TagRepository::class, function () {
            $repository = new EloquentTagRepository(new Tag());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheTagDecorator($repository);
        });

        $this->app->singleton(TagManager::class, function () {
            return new TagManagerRepository();
        });
    }

    protected function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }
        $this->app['blade.compiler']->directive('tags', function ($value) {
            return "<?php echo TagWidget::show([$value]); ?>";
        });
    }
}
