<?php

namespace Modules\Setting\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Setting\Blade\SettingDirective;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Events\Handlers\RegisterSettingSidebar;
use Modules\Setting\Facades\Settings as SettingsFacade;
use Modules\Setting\Repositories\Cache\CacheSettingDecorator;
use Modules\Setting\Repositories\Eloquent\EloquentSettingRepository;
use Modules\Setting\Repositories\SettingRepository;
use Modules\Setting\Support\Settings;

class SettingServiceProvider extends ServiceProvider
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

        $this->app->singleton('setting.settings', function ($app) {
            return new Settings($app[SettingRepository::class]);
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Settings', SettingsFacade::class);
        });

        $this->app->bind('setting.setting.directive', function () {
            return new SettingDirective();
        });

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('setting', RegisterSettingSidebar::class)
        );
    }

    public function boot()
    {
        $this->publishConfig('setting', 'permissions');
        $this->publishConfig('setting', 'config');
        $this->registerBladeTags();
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

    private function registerBindings()
    {
        $this->app->bind(SettingRepository::class, function () {
            $repository = new EloquentSettingRepository(new Setting());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheSettingDecorator($repository);
        });
        $this->app->bind(
            \Modules\Setting\Contracts\Setting::class,
            Settings::class
        );
    }

    private function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }
        $this->app['blade.compiler']->directive('setting', function ($value) {
            return "<?php echo SettingDirective::show([$value]); ?>";
        });
    }
}
