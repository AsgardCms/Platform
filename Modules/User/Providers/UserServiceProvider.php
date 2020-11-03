<?php

namespace Modules\User\Providers;

use Cartalyst\Sentinel\Laravel\SentinelServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\User\Console\GrantModulePermissionsCommand;
use Modules\User\Console\RemoveModulePermissionsCommand;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\UserToken;
use Modules\User\Events\Handlers\RegisterUserSidebar;
use Modules\User\Guards\Sentinel;
use Modules\User\Http\Middleware\AuthorisedApiToken;
use Modules\User\Http\Middleware\AuthorisedApiTokenAdmin;
use Modules\User\Http\Middleware\GuestMiddleware;
use Modules\User\Http\Middleware\LoggedInMiddleware;
use Modules\User\Http\Middleware\TokenCan;
use Modules\User\Repositories\Cache\CacheUserTokenDecorator;
use Modules\User\Repositories\Eloquent\EloquentUserTokenRepository;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Repositories\UserTokenRepository;

class UserServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @var array
     */
    protected $providers = [
        'Sentinel' => SentinelServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $middleware = [
        'auth.guest' => GuestMiddleware::class,
        'logged.in' => LoggedInMiddleware::class,
        'api.token' => AuthorisedApiToken::class,
        'api.token.admin' => AuthorisedApiTokenAdmin::class,
        'token-can' => TokenCan::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register($this->getUserPackageServiceProvider());

        $this->registerBindings();

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('user', RegisterUserSidebar::class)
        );
        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('users', Arr::dot(trans('user::users')));
            $event->load('roles', Arr::dot(trans('user::roles')));
        });
        $this->commands([
            GrantModulePermissionsCommand::class,
            RemoveModulePermissionsCommand::class,
        ]);

        app('router')->bind('role', function ($id) {
            return app(RoleRepository::class)->find($id);
        });
        app('router')->bind('user', function ($id) {
            return app(UserRepository::class)->find($id);
        });
        app('router')->bind('userTokenId', function ($id) {
            return app(UserTokenRepository::class)->find($id);
        });
    }

    /**
     */
    public function boot()
    {
        $this->registerMiddleware();

        $this->publishes([
            __DIR__ . '/../Resources/views' => base_path('resources/views/asgard/user'),
        ]);

        $this->publishConfig('user', 'permissions');
        $this->publishConfig('user', 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Auth::extend('sentinel-guard', function () {
            return new Sentinel();
        });
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
        $driver = config('asgard.user.config.driver', 'Sentinel');

        $this->app->bind(
            UserRepository::class,
            "Modules\\User\\Repositories\\{$driver}\\{$driver}UserRepository"
        );
        $this->app->bind(
            RoleRepository::class,
            "Modules\\User\\Repositories\\{$driver}\\{$driver}RoleRepository"
        );
        $this->app->bind(
            Authentication::class,
            "Modules\\User\\Repositories\\{$driver}\\{$driver}Authentication"
        );
        $this->app->bind(UserTokenRepository::class, function () {
            $repository = new EloquentUserTokenRepository(new UserToken());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheUserTokenDecorator($repository);
        });
    }

    private function registerMiddleware()
    {
        foreach ($this->middleware as $name => $class) {
            $this->app['router']->aliasMiddleware($name, $class);
        }
    }

    private function getUserPackageServiceProvider()
    {
        $driver = config('asgard.user.config.driver', 'Sentinel');

        if (!isset($this->providers[$driver])) {
            throw new \Exception("Driver [{$driver}] does not exist");
        }

        return $this->providers[$driver];
    }
}
