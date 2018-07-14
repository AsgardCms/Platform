<?php

namespace Modules\Media\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Media\Blade\MediaMultipleDirective;
use Modules\Media\Blade\MediaSingleDirective;
use Modules\Media\Blade\MediaThumbnailDirective;
use Modules\Media\Console\RefreshThumbnailCommand;
use Modules\Media\Contracts\DeletingMedia;
use Modules\Media\Contracts\StoringMedia;
use Modules\Media\Entities\File;
use Modules\Media\Events\FolderIsDeleting;
use Modules\Media\Events\FolderWasCreated;
use Modules\Media\Events\FolderWasUpdated;
use Modules\Media\Events\Handlers\CreateFolderOnDisk;
use Modules\Media\Events\Handlers\DeleteAllChildrenOfFolder;
use Modules\Media\Events\Handlers\DeleteFolderOnDisk;
use Modules\Media\Events\Handlers\HandleMediaStorage;
use Modules\Media\Events\Handlers\RegisterMediaSidebar;
use Modules\Media\Events\Handlers\RemovePolymorphicLink;
use Modules\Media\Events\Handlers\RenameFolderOnDisk;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\Eloquent\EloquentFileRepository;
use Modules\Media\Repositories\Eloquent\EloquentFolderRepository;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\Repositories\FolderRepository;
use Modules\Tag\Repositories\TagManager;

class MediaServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
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
        $this->registerBindings();

        $this->registerCommands();

        $this->app->bind('media.single.directive', function () {
            return new MediaSingleDirective();
        });
        $this->app->bind('media.multiple.directive', function () {
            return new MediaMultipleDirective();
        });
        $this->app->bind('media.thumbnail.directive', function () {
            return new MediaThumbnailDirective();
        });

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('media', RegisterMediaSidebar::class)
        );

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('media', array_dot(trans('media::media')));
            $event->load('folders', array_dot(trans('media::folders')));
        });

        app('router')->bind('media', function ($id) {
            return app(FileRepository::class)->find($id);
        });
    }

    public function boot(DispatcherContract $events)
    {
        $this->publishConfig('media', 'config');
        $this->publishConfig('media', 'permissions');
        $this->publishConfig('media', 'assets');

        $events->listen(StoringMedia::class, HandleMediaStorage::class);
        $events->listen(DeletingMedia::class, RemovePolymorphicLink::class);
        $events->listen(FolderWasCreated::class, CreateFolderOnDisk::class);
        $events->listen(FolderWasUpdated::class, RenameFolderOnDisk::class);
        $events->listen(FolderIsDeleting::class, DeleteFolderOnDisk::class);
        $events->listen(FolderIsDeleting::class, DeleteAllChildrenOfFolder::class);

        $this->app[TagManager::class]->registerNamespace(new File());
        $this->registerThumbnails();
        $this->registerBladeTags();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
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
        $this->app->bind(FileRepository::class, function () {
            return new EloquentFileRepository(new File());
        });
        $this->app->bind(FolderRepository::class, function () {
            return new EloquentFolderRepository(new File());
        });
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
        $this->app->singleton('command.media.refresh', function ($app) {
            return new RefreshThumbnailCommand($app['Modules\Media\Repositories\FileRepository']);
        });

        $this->commands('command.media.refresh');
    }

    private function registerThumbnails()
    {
        $this->app[ThumbnailManager::class]->registerThumbnail('smallThumb', [
            'resize' => [
                'width' => 50,
                'height' => null,
                'callback' => function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                },
            ],
        ]);
        $this->app[ThumbnailManager::class]->registerThumbnail('mediumThumb', [
            'resize' => [
                'width' => 180,
                'height' => null,
                'callback' => function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                },
            ],
        ]);
    }

    private function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }
        $this->app['blade.compiler']->directive('mediaSingle', function ($value) {
            return "<?php echo MediaSingleDirective::show([$value]); ?>";
        });
        $this->app['blade.compiler']->directive('mediaMultiple', function ($value) {
            return "<?php echo MediaMultipleDirective::show([$value]); ?>";
        });
        $this->app['blade.compiler']->directive('thumbnail', function ($value) {
            return "<?php echo MediaThumbnailDirective::show([$value]); ?>";
        });
    }
}
