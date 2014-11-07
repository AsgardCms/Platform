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

View::addNamespace('workshop', __DIR__ . '/Resources/views/');

Lang::addNamespace('workshop', __DIR__ . '/Resources/lang/');

Config::addNamespace('workshop', __DIR__ . '/Config/');

require __DIR__ . '/composers.php';
