<?php

/*
|--------------------------------------------------------------------------
| Language Settings
|--------------------------------------------------------------------------
*/
$lang = null;

if (App::environment() == 'testing') {
    $lang = 'fr';
}

LaravelLocalization::setLocale($lang);
