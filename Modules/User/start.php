<?php

/*
|--------------------------------------------------------------------------
| Register The Module Namespaces
|--------------------------------------------------------------------------
|
| Here is you can register the namespace for this module.
| You may to edit this namespace if you want.
|
*/

View::addNamespace('user', __DIR__ . '/Resources/views/');

Lang::addNamespace('user', __DIR__ . '/Resources/lang/');

Config::addNamespace('user', __DIR__ . '/Config/');

/*
|--------------------------------------------------------------------------
| Require The Routes file.
|--------------------------------------------------------------------------
|
| Next, this module will load filters and routes file.
|
*/

require __DIR__ . '/Http/routes.php';
require __DIR__ . '/composers.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/listeners.php';