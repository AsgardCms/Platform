<?php

Route::group(['prefix' => Config::get('core::core.admin-prefix').'/workshop', 'namespace' => 'Modules\Workshop\Http\Controllers'],
    function () {
        Route::get('modules', ['as' => 'dashboard.modules.index', 'uses' => 'ModulesController@index']);
        Route::post('modules', ['as' => 'dashboard.modules.store', 'uses' => 'ModulesController@store']);
        # Workbench
        Route::get('workbench', ['as' => 'dashboard.workbench.index', 'uses' => 'WorkbenchController@index']);
        Route::post('generate', ['as' => 'dashboard.workbench.generate.index', 'uses' => 'WorkbenchController@generate']);
    }
);