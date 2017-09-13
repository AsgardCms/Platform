<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->get('tags/namespace', [
    'as' => 'api.tag.tag.by-namespace',
    'uses' => 'TagByNamespaceController',
    'middleware' => 'token-can:tag.tags.index',
]);
