<?php

namespace Modules\Core\Tests;

use Intervention\Image\ImageServiceProvider;
use Modules\Page\Database\Seeders\PageDatabaseSeeder;
use Modules\User\Database\Seeders\SentinelGroupSeedTableSeeder;
use Modules\User\Database\Seeders\SentinelUserSeedTableSeeder;

abstract class TestBrowserTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', [
            '--database' => 'sqlite',
        ]);
        $this->artisan('db:seed', ['--class' => PageDatabaseSeeder::class]);
        $this->artisan('db:seed', ['--class' => SentinelGroupSeedTableSeeder::class]);
        $this->artisan('db:seed', ['--class' => SentinelUserSeedTableSeeder::class]);
    }

    /** @test */
    public function it_is_true()
    {
        $response = $this->get('/en/backend');

        $response->assertStatus(200);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Nwidart\Modules\LaravelModulesServiceProvider::class,
            \Modules\Core\Providers\CoreServiceProvider::class,
            \Modules\Core\Providers\AssetServiceProvider::class,
            \Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
            \Maatwebsite\Sidebar\SidebarServiceProvider::class,
            \FloatingPoint\Stylist\StylistServiceProvider::class,
            //\Modules\Dashboard\Providers\DashboardServiceProvider::class,
            \Modules\Media\Providers\MediaServiceProvider::class,
            \Modules\Media\Image\ImageServiceProvider::class,
            ImageServiceProvider::class,
            \Modules\Page\Providers\PageServiceProvider::class,
            \Modules\Setting\Providers\SettingServiceProvider::class,
            \Modules\Tag\Providers\TagServiceProvider::class,
            \Modules\Translation\Providers\TranslationServiceProvider::class,
            \Modules\User\Providers\UserServiceProvider::class,
            \Modules\Workshop\Providers\WorkshopServiceProvider::class,
        ];
    }
}
