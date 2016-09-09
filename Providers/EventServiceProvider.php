<?php

namespace Modules\Menu\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Menu\Events\Handlers\RootMenuItemCreator;
use Modules\Menu\Events\MenuWasCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MenuWasCreated::class => [
            RootMenuItemCreator::class,
        ],
    ];
}
