<?php

view()->composer(
    [
        'workshop::admin.workbench.tabs.migrate',
        'workshop::admin.workbench.tabs.seed',
    ],
    'Modules\Workshop\Composers\MigrateViewComposer'
);
