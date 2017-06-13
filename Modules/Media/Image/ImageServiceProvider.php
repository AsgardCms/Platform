<?php

namespace Modules\Media\Image;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Media\Image\Intervention\InterventionFactory;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageFactoryInterface::class, InterventionFactory::class);

        $this->app->singleton(ThumbnailManager::class, function () {
            return new ThumbnailManagerRepository();
        });

        $this->app->singleton('imagy', function ($app) {
            $factory = new InterventionFactory();

            return new Imagy($factory, $app[ThumbnailManager::class], $app['config']);
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Imagy', Facade\Imagy::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['imagy'];
    }
}
