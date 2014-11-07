<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function(Router $router)
{
    $router->group(['prefix' => Config::get('core::core.admin-prefix'), 'namespace' => 'Modules\Workshop\Http\Controllers'],
        function (Router $router) {
            $router->get('modules', ['as' => 'dashboard.modules.index', 'uses' => 'ModulesController@index']);
            $router->post('modules', ['as' => 'dashboard.modules.store', 'uses' => 'ModulesController@store']);
            # Workbench
            $router->get('workbench', ['as' => 'dashboard.workbench.index', 'uses' => 'WorkbenchController@index']);
            $router->post('generate', ['as' => 'dashboard.workbench.generate.index', 'uses' => 'WorkbenchController@generate']);
            $router->post('migrate', ['as' => 'dashboard.workbench.migrate.index', 'uses' => 'WorkbenchController@migrate']);
            $router->post('install', ['as' => 'dashboard.workbench.install.index', 'uses' => 'WorkbenchController@install']);
            $router->post('seed', ['as' => 'dashboard.workbench.seed.index', 'uses' => 'WorkbenchController@seed']);
        }
    );
});
