<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\SidebarManager;
use Modules\Core\Sidebar\AdminSidebar;

class SidebarServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
    }

    public function boot(SidebarManager $manager)
    {
        if ($this->app['asgard.onBackend'] === true) {
            $manager->register(AdminSidebar::class);
        }
    }
}
