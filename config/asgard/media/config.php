<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Choose which filesystem you wish to use to store the media
    |--------------------------------------------------------------------------
    | Choose one or more of the filesystems you configured
    | in app/config/filesystems.php
    | Supported: "local", "s3"
    */
    'filesystem' => 'local',
    /*
    |--------------------------------------------------------------------------
    | The path where the media files will be uploaded
    |--------------------------------------------------------------------------
    | Note: Trailing slash is required
    */
    'files-path' => '/assets/media/',
    /*
    |--------------------------------------------------------------------------
    | Specify all the allowed file extensions a user can upload on the server
    |--------------------------------------------------------------------------
    */
    'allowed-types' => '.jpg,.png',
    /*
    |--------------------------------------------------------------------------
    | Determine the max file size upload rate
    | Defined in MB
    |--------------------------------------------------------------------------
    */
    'max-file-size' => '5',

    /*
    |--------------------------------------------------------------------------
    | Determine the max total media folder size
    |--------------------------------------------------------------------------
    | Expressed in bytes
    */
    'max-total-size' => 1000000000,

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
