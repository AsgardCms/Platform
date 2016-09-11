<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Modules\User\Contracts\Authentication;

class PermissionMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var Route
     */
    private $route;

    /**
     * @param Authentication $auth
     * @param Route          $route
     */
    public function __construct(Authentication $auth, Route $route)
    {
        $this->auth = $auth;
        $this->route = $route;
    }

    /**
     * @param Request  $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $action = $this->route->getActionName();
        $actionMethod = substr($action, strpos($action, "@") + 1);

        $segmentPosition = $this->getSegmentPosition($request);
        $moduleName = $this->getModuleName($request, $segmentPosition);
        $entityName = $this->getEntityName($request, $segmentPosition);
        $permission = $this->getPermission($moduleName, $entityName, $actionMethod);

        if (!$this->auth->hasAccess($permission)) {
            return redirect()->back()->withError(trans('core::core.permission denied', ['permission' => $permission]));
        }

        return $next($request);
    }

    /**
     * Get the correct segment position based on the locale or not
     *
     * @param $request
     * @return mixed
     */
    private function getSegmentPosition(Request $request)
    {
        $segmentPosition = config('laravellocalization.hideDefaultLocaleInURL', false) ? 3 : 4;

        if ($request->segment($segmentPosition) == config('asgard.core.core.admin-prefix')) {
            return ++ $segmentPosition;
        }

        return $segmentPosition;
    }

    /**
     * @param $moduleName
     * @param $entityName
     * @param $actionMethod
     * @return string
     */
    private function getPermission($moduleName, $entityName, $actionMethod)
    {
        return ltrim($moduleName . '.' . $entityName . '.' . $actionMethod, '.');
    }

    /**
     * @param Request $request
     * @param         $segmentPosition
     * @return string
     */
    protected function getModuleName(Request $request, $segmentPosition)
    {
        return $request->segment($segmentPosition - 1);
    }

    /**
     * @param Request $request
     * @param         $segmentPosition
     * @return string
     */
    protected function getEntityName(Request $request, $segmentPosition)
    {
        $entityName = $request->segment($segmentPosition);

        return $entityName ?: 'dashboard';
    }
}
