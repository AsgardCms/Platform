<?php namespace Modules\Media\Image;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Media\Image\Intervention\InterventionFactory;

class ImageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->package('nwidart/imagy');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Modules\Media\Image\ImageFactoryInterface',
            'Modules\Media\Image\Intervention\InterventionFactory'
        );

        $this->app['imagy'] = $this->app->share(function ($app) {
            $factory = new InterventionFactory;
            $thumbnailManager = new ThumbnailsManager($app['config'], $app['modules']);

            return new Imagy($factory, $thumbnailManager, $app['config']);
        });

        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Modules\Media\Image\Facade\Imagy', 'Imagy');
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
