<?php
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

View::composer('core::partials.sidebar-nav', 'Modules\User\Composers\SidebarViewComposer');
View::composer([
        'user::admin.roles.partials.permissions',
        'user::admin.roles.partials.permissions-create',
        'user::admin.users.partials.permissions',
        'user::admin.users.partials.permissions-create',
    ], 'Modules\User\Composers\PermissionsViewComposer');

View::composer(['core::partials.sidebar-nav', 'core::partials.top-nav'], function($view)
{
    $view->with('user', Sentinel::check());
});
