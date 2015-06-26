<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Define the way the Sidebar should be cached.
    | The cache store is defined by the Laravel
    |
    | Available: null|static|user-based
    |
    */
    'cache' => [
        'method'   => 'user-based',
        'duration' => 1440
    ]
];
