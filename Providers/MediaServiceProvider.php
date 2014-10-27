<?php namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Media\Entities\File;
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
            function() {
                return new EloquentFileRepository(new File);
            }
        );
    }

}
