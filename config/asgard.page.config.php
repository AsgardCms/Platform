<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Partials to include on page views
    |--------------------------------------------------------------------------
    | List the partials you wish to include on the different type page views
    | The content of those fields well be caught by the PageWasCreated and PageWasEdited events
    */
    'partials' => [
        'translatable' => [
            'create' => [],
            'edit' => [],
        ],
        'normal' => [
            'create' => [],
            'edit' => [],
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Dynamic relations
    |--------------------------------------------------------------------------
    | Add relations that will be dynamically added to the Page entity
    */
    'relations' => [],
    /*
    |--------------------------------------------------------------------------
    | Array of middleware that will be applied on the page module front end routes
    |--------------------------------------------------------------------------
    */
    'middleware' => [
        'shortcode' => \Modules\Core\Http\Middleware\ShortcodeMiddleware::class
    ]
];
