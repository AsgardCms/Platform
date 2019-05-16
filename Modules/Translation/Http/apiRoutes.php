<?php

use Illuminate\Routing\Router;

/** @var $router Router */
$router->group(['prefix' => '/translation', 'middleware' => 'api.token'], function (Router $router) {
    $router->post('update', [
        'uses' => 'TranslationController@update',
        'as' => 'api.translation.translations.update',
        'middleware' => 'token-can:translation.translations.edit',
    ]);
    $router->post('clearCache', [
        'uses' => 'TranslationController@clearCache',
        'as' => 'api.translation.translations.clearCache',
    ]);
    $router->post('revisions', [
        'uses' => 'TranslationController@revisions',
        'as' => 'api.translation.translations.revisions',
    ]);
    $router->get('/', [
        'uses' => 'AllTranslationController',
        'as' => 'api.translation.translations.all',
    ]);
    $router->get('list-locales-for-select', [
        'uses' => 'LocaleController@listLocalesForSelect',
        'as' => 'api.translation.translations.list-locales-for-select',
    ]);
});
