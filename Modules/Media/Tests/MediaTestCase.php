<?php

namespace Modules\Media\Tests;

use Collective\Html\FormFacade;
use Collective\Html\HtmlFacade;
use Collective\Html\HtmlServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\TranslationServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Maatwebsite\Sidebar\SidebarServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Media\Providers\MediaServiceProvider;
use Modules\Tag\Providers\TagServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Nwidart\Modules\Providers\BootstrapServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class MediaTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TranslationServiceProvider::class,
            LaravelModulesServiceProvider::class,
            BootstrapServiceProvider::class,
            CoreServiceProvider::class,
            TagServiceProvider::class,
            \Modules\Media\Image\ImageServiceProvider::class,
            MediaServiceProvider::class,
            ImageServiceProvider::class,
            LaravelLocalizationServiceProvider::class,
            HtmlServiceProvider::class,
            SidebarServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LaravelLocalization' => LaravelLocalization::class,
            'Validator' => Validator::class,
            'Form' => FormFacade::class,
            'Html' => HtmlFacade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = __DIR__ . '/..';
        $app['config']->set('asgard.media.config', ['filesystem' => 'local']);
        $app['config']->set('modules', [
            'namespace' => 'Modules',
        ]);
        $app['config']->set('modules.paths.modules', realpath(__DIR__ . '/../Modules'));
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
        $app['config']->set('translatable.locales', ['en', 'fr']);
        $app['config']->set('app.url', 'http://localhost');
        $app['config']->set('filesystems.disks.local.url', 'http://localhost');
        $app['config']->set('filesystems.disks.local.visibility', 'public');
    }
}
