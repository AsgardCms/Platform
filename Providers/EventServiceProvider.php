<?php

namespace Modules\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Maatwebsite\Sidebar\Domain\Events\FlushesSidebarCache;
use Modules\User\Events\Handlers\SendRegistrationConfirmationEmail;
use Modules\User\Events\Handlers\SendResetCodeEmail;
use Modules\User\Events\RoleWasUpdated;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Events\UserHasRegistered;
use Modules\User\Events\UserWasUpdated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserHasRegistered::class => [
            SendRegistrationConfirmationEmail::class,
        ],
        UserHasBegunResetProcess::class => [
            SendResetCodeEmail::class,
        ],
        UserWasUpdated::class => [
            FlushesSidebarCache::class,
        ],
        RoleWasUpdated::class => [
            FlushesSidebarCache::class,
        ],
    ];
}
