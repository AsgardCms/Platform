<?php namespace Modules\Core\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use Modules\Core\Contracts\Authentication;

class PermissionFilter
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function filter(Route $route, Request $request)
    {
        $action = $route->getActionName();
        $actionMethod = substr($action, strpos($action, "@") + 1);

        $segmentPosition = $this->getSegmentPosition($request);

        if ($this->auth->hasAccess("{$request->segment($segmentPosition)}.$actionMethod"))
        {
            return;
        }

        Flash::error('Permission denied.');
        return Redirect::to('/' . Config::get('core::core.admin-prefix'));
    }

    /**
     * Get the correct segment position based on the locale or not
     * @param $request
     * @return mixed
     */
    private function getSegmentPosition(Request $request)
    {
        $segmentPosition = 2;

        if ($request->segment($segmentPosition) == Config::get('core::core.admin-prefix')) {
            return ++$segmentPosition;
        }

        return $segmentPosition;
    }
}
