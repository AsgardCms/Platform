<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Define which assets will be available through the asset manager
    |--------------------------------------------------------------------------
    | These assets are registered on the asset manager
    */
    'media-partial-assets' => [
        'jquery-ui.js' => ['module' => 'dashboard:vendor/jquery-ui/jquery-ui.min.js'],
        'media-partial.js' => ['module' => 'media:js/media-partial.js'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Define which default assets will always be included in your pages
    | through the asset pipeline
    |--------------------------------------------------------------------------
    */
    'media-partial-required-assets' => [
        'js' => [
            'jquery-ui.js',
            'media-partial.js',
        ],
    ],
];
