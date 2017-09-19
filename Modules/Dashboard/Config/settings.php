<?php

return [
    'welcome-title' => [
        'description' => 'dashboard::dashboard.welcome-title',
        'view' => 'text',
        'translatable' => true,
        'default' => 'Welcome on your backend!',
    ],
    'welcome-description' => [
        'description' => 'dashboard::dashboard.welcome-description',
        'view' => 'textarea',
        'translatable' => true,
        'default' => 'Did you know you can access the documentation by pressing F1?',
    ],
    'welcome-enabled' => [
        'description' => 'dashboard::dashboard.welcome-enabled',
        'view' => 'checkbox',
        'translatable' => false,
        'default' => '1',
    ],
];
