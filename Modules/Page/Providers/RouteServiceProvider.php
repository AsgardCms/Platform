<?php

namespace Modules\Page\Providers;

use Modules\Core\Providers\RoutingServiceProvider as CoreRoutingServiceProvider;

class RouteServiceProvider extends CoreRoutingServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     * @var string
     */
    protected $namespace = 'Modules\Page\Http\Controllers';

    /**
     * @return string
     */
    protected function getFrontendRoute()
    {
        return __DIR__ . '/../Http/frontendRoutes.php';
    }

    /**
     * @return string
     */
    protected function getBackendRoute()
    {
        return __DIR__ . '/../Http/backendRoutes.php';
    }

    /**
     * @return string
     */
    protected function getApiRoute()
    {
        return __DIR__ . '/../Http/apiRoutes.php';
    }
}
