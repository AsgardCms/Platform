<?php

/*
|--------------------------------------------------------------------------
| Language Settings
|--------------------------------------------------------------------------
*/
if (App::environment() == 'testing') {
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'fr_FR';
}
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

App::setLocale('fr');