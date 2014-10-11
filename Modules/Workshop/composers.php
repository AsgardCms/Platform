<?php

View::composer('core::partials.sidebar-nav', 'Modules\Workshop\Composers\SidebarViewComposer');
View::composer([
        'workshop::admin.workbench.tabs.migrate',
        'workshop::admin.workbench.tabs.seed',
    ], 'Modules\Workshop\Composers\MigrateViewComposer');
