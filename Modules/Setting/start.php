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

View::addNamespace('setting', __DIR__ . '/Resources/views/');

Lang::addNamespace('setting', __DIR__ . '/Resources/lang/');

Config::addNamespace('setting', __DIR__ . '/Config/');

require __DIR__ . '/composers.php';
