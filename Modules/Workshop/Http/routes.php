<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function()
{
    Route::group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Workshop\Http\Controllers'],
        function () {
            Route::get('modules', ['as' => 'dashboard.modules.index', 'uses' => 'ModulesController@index']);
            Route::post('modules', ['as' => 'dashboard.modules.store', 'uses' => 'ModulesController@store']);
            # Workbench
            Route::get('workbench', ['as' => 'dashboard.workbench.index', 'uses' => 'WorkbenchController@index']);
            Route::post('generate', ['as' => 'dashboard.workbench.generate.index', 'uses' => 'WorkbenchController@generate']);
            Route::post('migrate', ['as' => 'dashboard.workbench.migrate.index', 'uses' => 'WorkbenchController@migrate']);
            Route::post('install', ['as' => 'dashboard.workbench.install.index', 'uses' => 'WorkbenchController@install']);
            Route::post('seed', ['as' => 'dashboard.workbench.seed.index', 'uses' => 'WorkbenchController@seed']);
        }
    );
});
