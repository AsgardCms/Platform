<?php

View::composer('core::partials.sidebar-nav', 'Modules\User\Composers\SidebarViewComposer');
View::composer([
        'user::admin.partials.permissions',
        'user::admin.partials.permissions-create',
    ], 'Modules\User\Composers\PermissionsViewComposer');

View::composer(['core::partials.sidebar-nav', 'core::partials.top-nav'], 'Modules\User\Composers\UsernameViewComposer');
