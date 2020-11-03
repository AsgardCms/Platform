<?php

namespace Modules\User\Tests;

use Illuminate\View\ViewServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class BaseUserTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            ViewServiceProvider::class,
            LaravelModulesServiceProvider::class,
            CoreServiceProvider::class,
            UserServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = __DIR__ . '/..';
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('translatable.locales', ['en', 'fr']);
        $app['config']->set('modules.paths.modules', __DIR__ . '/../Modules');
        $app['config']->set('cartalyst.sentinel.users.model', \Modules\User\Entities\Sentinel\User::class);
        $app['config']->set('asgard.user.config.fillable', [
            'email',
            'password',
            'permissions',
            'first_name',
            'last_name',
        ]);
        $app['config']->set('asgard.user.config.login-columns', [
            'email',
        ]);
    }

    private function resetDatabase()
    {
        // Makes sure the migrations table is created
        $this->artisan('migrate', [
            '--database' => 'sqlite',
        ]);
        // We empty all tables
        $this->artisan('migrate:reset', [
            '--database' => 'sqlite',
        ]);
        // Migrate
        $this->artisan('migrate', [
            '--database' => 'sqlite',
        ]);
        $this->artisan('migrate');
    }
}
