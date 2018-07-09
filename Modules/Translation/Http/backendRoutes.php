<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' =>'/translation'], function (Router $router) {
    $router->get('translations', [
        'uses' => 'TranslationController@index',
        'as' => 'admin.translation.translation.index',
        'middleware' => 'can:translation.translations.index',
    ]);
    $router->get('translations/update/{translations}', [
        'uses' => 'TranslationController@update',
        'as' => 'admin.translation.translation.update',
        'middleware' => 'can:translation.translations.edit',
    ]);
    $router->get('translations/export', [
        'uses' => 'TranslationController@export',
        'as' => 'admin.translation.translation.export',
        'middleware' => 'can:translation.translations.export',
    ]);
    $router->post('translations/import', [
        'uses' => 'TranslationController@import',
        'as' => 'admin.translation.translation.import',
        'middleware' => 'can:translation.translations.import',
    ]);
});
