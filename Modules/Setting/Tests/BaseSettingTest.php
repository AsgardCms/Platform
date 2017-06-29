<?php

namespace Modules\Setting\Tests;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Sidebar\SidebarServiceProvider;
use Modules\Setting\Providers\SettingServiceProvider;
use Modules\Setting\Repositories\SettingRepository;
use Orchestra\Testbench\TestCase;

abstract class BaseSettingTest extends TestCase
{
    /**
     * @var SettingRepository
     */
    protected $settingRepository;

    public function setUp()
    {
        parent::setUp();

        $this->resetDatabase();

        $this->settingRepository = app(SettingRepository::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingServiceProvider::class,
            SidebarServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = __DIR__ . '/..';
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
        $app['config']->set('asgard.core.settings', [
            'site-name' => [
                'description' => 'core::settings.site-name',
                'view' => 'text',
                'translatable' => true,
            ],
            'template' => [
                'description' => 'core::settings.template',
                'view' => 'core::fields.select-theme',
            ],
            'locales' => [
                'description' => 'core::settings.locales',
                'view' => 'core::fields.select-locales',
                'translatable' => false,
            ],
        ]);
    }

    protected function getPackageAliases($app)
    {
        return ['Eloquent' => Model::class];
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
    }
}
