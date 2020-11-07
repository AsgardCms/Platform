<?php

namespace Modules\Page\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Sidebar\SidebarServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Page\Providers\PageServiceProvider;
use Modules\Page\Repositories\PageRepository;
use Modules\Tag\Providers\TagServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class BasePageTest extends TestCase
{
    /**
     * @var PageRepository
     */
    protected $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();

        $this->page = app(PageRepository::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelModulesServiceProvider::class,
            CoreServiceProvider::class,
            TagServiceProvider::class,
            PageServiceProvider::class,
            LaravelLocalizationServiceProvider::class,
            SidebarServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Eloquent' => Model::class,
            'LaravelLocalization' => LaravelLocalization::class,
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
        $app['config']->set('laravellocalization.supportedLocales', [
            'en' => ['asd' => 'asd'],
            'fr' => ['asd' => 'asd'],
        ]);
    }

    private function resetDatabase()
    {
        // Relative to the testbench app folder: vendors/orchestra/testbench/src/fixture
        $migrationsPath = 'Database/Migrations';
        $artisan = $this->app->make(Kernel::class);
        // Makes sure the migrations table is created
        $artisan->call('migrate', [
            '--database' => 'sqlite',
        ]);
        // We empty all tables
        $artisan->call('migrate:reset', [
            '--database' => 'sqlite',
        ]);
        // Migrate
        $artisan->call('migrate', [
            '--database' => 'sqlite',
        ]);
        $artisan->call('migrate', [
            '--database' => 'sqlite',
            '--path'     => 'Modules/Tag/Database/Migrations',
        ]);
    }
}
