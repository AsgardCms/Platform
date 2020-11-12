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
    | Define a class that will handle User presentation
    |--------------------------------------------------------------------------
    | Default: \Modules\User\Presenters\UserPresenter::class
    */
    'presenter' => \Modules\User\Presenters\UserPresenter::class,
    /*
    |--------------------------------------------------------------------------
    | Allow anonymous user registration
    |--------------------------------------------------------------------------
    */
    'allow_user_registration' => true,
    /*
    |--------------------------------------------------------------------------
    | The default role for new user registrations
    |--------------------------------------------------------------------------
    | Default: User
    */
    'default_role' => 'User',
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
    | Custom date fields
    |--------------------------------------------------------------------------
    | Set the fields that will be cast to Carbon dates
    */
    'dates' => [
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom casted fields
    |--------------------------------------------------------------------------
    | Set the fields that will be casted by Eloquent
    */
    'casts' => [
        'permissions' => 'json',
    ],
    /*
    |--------------------------------------------------------------------------
    | Dynamic relations
    |--------------------------------------------------------------------------
    | Add relations that will be dynamically added to the User entity
     */
    'relations' => [
//        'extension' => function (): \Illuminate\Database\Eloquent\Relations\BelongsTo {
//            return $this->belongsTo(UserExtension::class, 'user_id', 'id')->first();
//        }
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
        'resources' => true,
    ],
];
