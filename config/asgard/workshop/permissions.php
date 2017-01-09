<?php

return [
    'workshop.sidebar' => [
        'group' => 'workshop::workshop.show sidebar group',
    ],
    'workshop.modules' => [
        'index' => 'workshop::modules.list resource',
        'show' => 'workshop::modules.show resource',
        'update' => 'workshop::modules.update resource',
        'disable' => 'workshop::modules.disable resource',
        'enable' => 'workshop::modules.enable resource',
        'publish' => 'workshop::modules.publish assets',
    ],
    'workshop.themes' => [
        'index' => 'workshop::themes.list resource',
        'show' => 'workshop::themes.show resource',
        'publish' => 'workshop::themes.publish assets',
    ],
];
