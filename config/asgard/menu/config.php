<?php

return [
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
    | Default Menu Presenter
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    | Configure which Menu presenter will be used by default without
    | having to send it via the views
    */
    'default_menu_presenter' => \Modules\Menu\Presenters\MenuPresenter::class,
];
