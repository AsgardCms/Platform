<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Dashboard\Composers\WidgetViewComposer;

class WidgetServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('Modules\Dashboard\Composers\WidgetViewComposer', function () {
            return new WidgetViewComposer();
        });

        $this->app['view']->composer('dashboard::admin.dashboard', 'Modules\Dashboard\Composers\WidgetViewComposer');
    }
}
