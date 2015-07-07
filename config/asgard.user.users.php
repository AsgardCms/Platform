<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Define which user driver to use.
    |--------------------------------------------------------------------------
    | Current default and only option : Sentinel
    | Sentry option is outdated
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
    | Login column(s)
    |--------------------------------------------------------------------------
    | Define which column(s) you'd like to use to login with, currently
    | only supported by the Sentinel user driver
    */
    'login-columns' => ['email'],

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
];
