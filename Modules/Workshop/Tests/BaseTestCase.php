<?php

namespace Modules\Workshop\Tests;

use Collective\Html\FormFacade;
use Collective\Html\HtmlFacade;
use Collective\Html\HtmlServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Modules\Workshop\Providers\WorkshopServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelLocalizationServiceProvider::class,
            WorkshopServiceProvider::class,
            LaravelModulesServiceProvider::class,
            HtmlServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LaravelLocalization' => LaravelLocalization::class,
            'Form' => FormFacade::class,
            'Html' => HtmlFacade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('modules.namespace', 'Modules');
        $app['config']->set('app.locale', 'en');
        $app['config']->set('modules.paths.generator', [
            'assets' => 'Assets',
            'config' => 'Config',
            'command' => 'Console',
            'migration' => 'Database/Migrations',
            'model' => 'Entities',
            'repository' => 'Repositories',
            'seeder' => 'Database/Seeders',
            'controller' => 'Http/Controllers',
            'filter' => 'Http/Middleware',
            'request' => 'Http/Requests',
            'provider' => 'Providers',
            'lang' => 'Resources/lang',
            'views' => 'Resources/views',
            'test' => 'Tests',
            'event' => 'Events',
            'listener' => 'Events/Handlers',
        ]);
        $app['config']->set('laravellocalization.supportedLocales', [
            'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
            'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
        ]);
        $app['config']->set('modules.paths.modules', realpath(__DIR__ . '/../Modules'));
        $app['config']->set('stylist.themes.paths', [base_path('Themes')]);
        $app['config']->set('asgard.core.core.themes_path', base_path('Themes'));
    }
}
