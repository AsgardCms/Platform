<?php

/*
|--------------------------------------------------------------------------
| Language Settings
|--------------------------------------------------------------------------
*/
$lang = Request::getPreferredLanguage(['fr', 'en', 'en', 'de']);

if (App::environment() == 'testing') {
    $lang = 'fr';
}

App::setLocale('fr');