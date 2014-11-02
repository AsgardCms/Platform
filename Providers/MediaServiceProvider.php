<?php namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Media\Console\RefreshThumbnailCommand;
use Modules\Media\Entities\File;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\Intervention\InterventionFactory;
use Modules\Media\Image\ThumbnailsManager;
use Modules\Media\Repositories\Eloquent\EloquentFileRepository;

class MediaServiceProvider extends ServiceProvider
{
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
        $this->app->booted(function () {
            $this->registerBindings();
        });
        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Media\Repositories\FileRepository',
            function($app) {
                return new EloquentFileRepository(new File, $app['filesystem.disk']);
            }
        );
    }

    /**
     * Register all commands for this module
     */
    private function registerCommands()
    {
        $this->registerRefreshCommand();
    }

    /**
     * Register the refresh thumbnails command
     */
    private function registerRefreshCommand()
    {
        $this->app->bindShared('command.media.refresh', function($app) {
            $thumbnailManager = new ThumbnailsManager($app['config'], $app['modules']);
            $imagy = new Imagy(new InterventionFactory, $thumbnailManager, $app['config']);
            return new RefreshThumbnailCommand($imagy, $app['Modules\Media\Repositories\FileRepository']);
        });

        $this->commands(
            'command.media.refresh'
        );
    }
}
