<?php

return [
    'workshop.modules' => [
        'index' => 'workshop::modules.list resource',
        'show' => 'workshop::modules.show resource',
        'update' => 'workshop::modules.update resource',
        'disable' => 'workshop::modules.disable resource',
        'enable' => 'workshop::modules.enable resource',
    ],
    'workshop.themes' => [
        'index' => 'workshop::themes.list resource',
        'show' => 'workshop::themes.show resource',
    ],
];
