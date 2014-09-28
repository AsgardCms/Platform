<?php namespace Modules\Core\Http\Filters;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class PermissionFilter
{
    public function filter($route, $request)
    {
        $action = $route->getActionName();
        $actionMethod = substr($action, strpos($action, "@") + 1);

        if (Sentinel::hasAccess("{$request->segment(2)}.$actionMethod"))
        {
            return;
        }

        Flash::error('Permission denied.');
        return Redirect::to('/' . Config::get('core::core.admin-prefix'));
    }
}