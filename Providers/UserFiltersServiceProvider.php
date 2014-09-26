<?php namespace Modules\User\Providers;

use Illuminate\Routing\FilterServiceProvider as ServiceProvider;

class UserFiltersServiceProvider extends ServiceProvider
{
    protected $filters = [
        'permissions' => 'Modules\User\Http\Filters\PermissionFilter',
    ];
}