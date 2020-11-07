<?php

namespace Modules\Translation\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Composers\CurrentUserViewComposer;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Translation\Console\BuildTranslationsCacheCommand;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Entities\TranslationTranslation;
use Modules\Translation\Events\Handlers\RegisterTranslationSidebar;
use Modules\Translation\Repositories\Cache\CacheLocaleDecorator;
use Modules\Translation\Repositories\Cache\CacheTranslationDecorator;
use Modules\Translation\Repositories\Eloquent\EloquentLocaleRepository;
use Modules\Translation\Repositories\Eloquent\EloquentTranslationRepository;
use Modules\Translation\Repositories\File\FileTranslationRepository as FileDiskTranslationRepository;
use Modules\Translation\Repositories\FileTranslationRepository;
use Modules\Translation\Repositories\LocaleRepository;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\TranslationLoader;

class TranslationServiceProvider extends ServiceProvider
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
        $this->registerConsoleCommands();

        view()->composer('translation::admin.translations.index', CurrentUserViewComposer::class);

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('translation', RegisterTranslationSidebar::class)
        );

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('translations', Arr::dot(trans('translation::translations')));
            $event->load('locales', Arr::dot(trans('translation::locales')));
        });

        app('router')->bind('translations', function ($id) {
            return TranslationTranslation::find($id);
        });
    }

    public function boot()
    {
        $this->publishConfig('translation', 'config');
        $this->publishConfig('translation', 'permissions');

        $this->registerValidators();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole() === true) {
            return;
        }

        if ($this->shouldRegisterCustomTranslator()) {
            $this->registerCustomTranslator();
        }
    }

    /**
     * Should we register the Custom Translator?
     * @return bool
     */
    protected function shouldRegisterCustomTranslator()
    {
        if (false === config('app.translations-gui', true)) {
            return false;
        }

        if (false === env('INSTALLED', false)) {
            return false;
        }

        if (false === Schema::hasTable((new Translation)->getTable())) {
            return false;
        }

        return true;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function registerBindings()
    {
        $this->app->bind(TranslationRepository::class, function () {
            $repository = new EloquentTranslationRepository(new Translation());

            return new CacheTranslationDecorator($repository);
        });

        $this->app->bind(FileTranslationRepository::class, function ($app) {
            return new FileDiskTranslationRepository($app['files'], $app['translation.loader']);
        });

        $this->app->bind(
            LocaleRepository::class,
            function () {
                $repository = new EloquentLocaleRepository();

                return new CacheLocaleDecorator($repository);
            }
        );
    }

    private function registerConsoleCommands()
    {
        $this->commands([
            BuildTranslationsCacheCommand::class,
        ]);
    }

    protected function registerCustomTranslator()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app['config']['app.locale'];

            $trans = new \Illuminate\Translation\Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    private function registerValidators()
    {
        Validator::extend('extensions', function ($attribute, $value, $parameters) {
            return in_array($value->getClientOriginalExtension(), $parameters);
        });

        Validator::replacer('extensions', function ($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':values'], [$attribute, implode(',', $parameters)], $message);
        });
    }
}
