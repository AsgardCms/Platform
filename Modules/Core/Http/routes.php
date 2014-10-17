<?php

/*
|--------------------------------------------------------------------------
| Language Settings
|--------------------------------------------------------------------------
*/
$lang = Request::getPreferredLanguage(['fr', 'en', 'de']);

if (App::environment() == 'testing') {
    $lang = 'fr';
}

App::setLocale($lang);
