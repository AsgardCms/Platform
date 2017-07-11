<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Stubs Folder
    |--------------------------------------------------------------------------
    | You can specify place from which you would like to use stubs.
    | e.g. "Modules/<module-name>/Resources/views/stubs"
    | Only the customized stubs need to be in this folder.
    | All other stubs will be loaded from Workshop Module folder.
    | No custom stubs folder: null
    */
    'custom-stubs-folder' => null,

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
