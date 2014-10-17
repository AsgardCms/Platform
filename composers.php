<?php
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

View::composer('core::partials.sidebar-nav', 'Modules\User\Composers\SidebarViewComposer');
View::composer([
        'user::admin.partials.permissions',
        'user::admin.partials.permissions-create',
    ], 'Modules\User\Composers\PermissionsViewComposer');

View::composer(['core::partials.sidebar-nav', 'core::partials.top-nav'], function($view)
{
    $view->with('user', Sentinel::check());
});
