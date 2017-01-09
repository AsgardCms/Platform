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
    'relations' => [
//        'extension' => function ($self) {
//            return $self->belongsTo(PageExtension::class, 'id', 'page_id')->first();
//        }
    ],
    /*
    |--------------------------------------------------------------------------
    | Array of middleware that will be applied on the page module front end routes
    |--------------------------------------------------------------------------
    */
    'middleware' => [],
    /*
     |--------------------------------------------------------------------------
     | Array of directories to ignore when selecting the template for a page
     |--------------------------------------------------------------------------
     */
    'template-ignored-directories' => [
        'layouts',
        'partials',
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Sidebar Class
    |--------------------------------------------------------------------------
    | If you want to customise the admin sidebar ordering or grouping
    | You can define your own sidebar class for this module.
    | No custom sidebar: null
    */
    'custom-sidebar' => null,

    /*
    |--------------------------------------------------------------------------
    | Load additional view namespaces for a module
    |--------------------------------------------------------------------------
    | You can specify place from which you would like to use module views.
    | You can use any combination, but generally it's advisable to add only one,
    | extra view namespace.
    | By default every extra namespace will be set to false.
    */
    'useViewNamespaces' => [
        // Read module views from /Themes/<backend-theme-name>/views/modules/<module-name>
        'backend-theme' => false,
        // Read module views from /Themes/<frontend-theme-name>/views/modules/<module-name>
        'frontend-theme' => false,
        // Read module views from /resources/views/asgard/<module-name>
        'resources' => false,
    ],
];
