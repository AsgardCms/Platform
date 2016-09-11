<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Define which user driver to use.
    |--------------------------------------------------------------------------
    | Current default and only option : Sentinel
    */
    'driver' => 'Sentinel',
    /*
    |--------------------------------------------------------------------------
    | Define which route to redirect to after a successful login
    |--------------------------------------------------------------------------
    */
    'redirect_route_after_login' => 'homepage',
    /*
    |--------------------------------------------------------------------------
    | Define which route the user should be redirected to after accessing
    | a resource that requires to be logged in
    |--------------------------------------------------------------------------
    */
    'redirect_route_not_logged_in' => 'auth/login',
    /*
    |--------------------------------------------------------------------------
    | Login column(s)
    |--------------------------------------------------------------------------
    | Define which column(s) you'd like to use to login with, currently
    | only supported by the Sentinel user driver
    */
    'login-columns' => ['email'],
    /*
    |--------------------------------------------------------------------------
    | Allow anonymous user registration
    |--------------------------------------------------------------------------
    */
    'allow_user_registration' => true,
    /*
    |--------------------------------------------------------------------------
    | Fillable user fields
    |--------------------------------------------------------------------------
    | Set the fillable user fields, those fields will be mass assigned
    */
    'fillable' => [
        'email',
        'password',
        'permissions',
        'first_name',
        'last_name',
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
];
