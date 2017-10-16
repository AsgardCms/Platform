<?php

namespace Modules\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Menu\Blade\MenuDirective;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Events\Handlers\RegisterMenuSidebar;
use Modules\Menu\Repositories\Cache\CacheMenuDecorator;
use Modules\Menu\Repositories\Cache\CacheMenuItemDecorator;
use Modules\Menu\Repositories\Eloquent\EloquentMenuItemRepository;
use Modules\Menu\Repositories\Eloquent\EloquentMenuRepository;
use Modules\Menu\Repositories\MenuItemRepository;
use Modules\Menu\Repositories\MenuRepository;
use Nwidart\Menus\Facades\Menu as MenuFacade;
use Nwidart\Menus\MenuBuilder as Builder;
use Nwidart\Menus\MenuItem as PingpongMenuItem;

class MenuServiceProvider extends ServiceProvider
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

        $this->app->bind('menu.menu.directive', function () {
            return new MenuDirective();
        });

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('menu', RegisterMenuSidebar::class)
        );

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('menu', array_dot(trans('menu::menu')));
            $event->load('menu-items', array_dot(trans('menu::menu-items')));
        });
    }

    /**
     * Register all online menus on the Pingpong/Menu package
     */
    public function boot()
    {
        $this->registerMenus();
        $this->registerBladeTags();
        $this->publishConfig('menu', 'permissions');
        $this->publishConfig('menu', 'config');
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

    /**
     * Register class binding
     */
    private function registerBindings()
    {
        $this->app->bind(MenuRepository::class, function () {
            $repository = new EloquentMenuRepository(new Menu());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheMenuDecorator($repository);
        });

        $this->app->bind(MenuItemRepository::class, function () {
            $repository = new EloquentMenuItemRepository(new Menuitem());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheMenuItemDecorator($repository);
        });
    }

    /**
     * Add a menu item to the menu
     * @param Menuitem $item
     * @param Builder $menu
     */
    public function addItemToMenu(Menuitem $item, Builder $menu)
    {
        if ($this->hasChildren($item)) {
            $this->addChildrenToMenu(
                $item->title,
                $item->items,
                $menu,
                [
                    'icon' => $item->icon,
                    'target' => $item->target,
                    'class' => $item->class,
                ]
            );
        } else {
            $target = $item->link_type != 'external' ? $item->locale . '/' . $item->uri : $item->url;
            $menu->url(
                $target,
                $item->title,
                [
                    'target' => $item->target,
                    'icon' => $item->icon,
                    'class' => $item->class,
                ]
            );
        }
    }

    /**
     * Add children to menu under the give name
     *
     * @param string $name
     * @param object $children
     * @param Builder|MenuItem $menu
     */
    private function addChildrenToMenu($name, $children, $menu, $attribs = [])
    {
        $menu->dropdown($name, function (PingpongMenuItem $subMenu) use ($children) {
            foreach ($children as $child) {
                $this->addSubItemToMenu($child, $subMenu);
            }
        }, 0, $attribs);
    }

    /**
     * Add children to the given menu recursively
     * @param Menuitem $child
     * @param PingpongMenuItem $sub
     */
    private function addSubItemToMenu(Menuitem $child, PingpongMenuItem $sub)
    {
        if ($this->hasChildren($child)) {
            $this->addChildrenToMenu($child->title, $child->items, $sub);
        } else {
            $target = $child->link_type != 'external' ? $child->locale . '/' . $child->uri : $child->url;
            $sub->url($target, $child->title, 0, ['icon' => $child->icon, 'target' => $child->target, 'class' => $child->class]);
        }
    }

    /**
     * Check if the given menu item has children
     *
     * @param  object $item
     * @return bool
     */
    private function hasChildren($item)
    {
        return $item->items->count() > 0;
    }

    /**
     * Register the active menus
     */
    private function registerMenus()
    {
        if ($this->app['asgard.isInstalled'] === false ||
            $this->app['asgard.onBackend'] === true ||
            $this->app->runningInConsole() === true
        ) {
            return;
        }

        $menu = $this->app->make(MenuRepository::class);
        $menuItem = $this->app->make(MenuItemRepository::class);
        foreach ($menu->allOnline() as $menu) {
            $menuTree = $menuItem->getTreeForMenu($menu->id);
            MenuFacade::create($menu->name, function (Builder $menu) use ($menuTree) {
                foreach ($menuTree as $menuItem) {
                    $this->addItemToMenu($menuItem, $menu);
                }
            });
        }
    }

    /**
     * Register menu blade tags
     */
    protected function registerBladeTags()
    {
        if (app()->environment() === 'testing') {
            return;
        }

        $this->app['blade.compiler']->directive('menu', function ($arguments) {
            return "<?php echo MenuDirective::show([$arguments]); ?>";
        });
    }
}
