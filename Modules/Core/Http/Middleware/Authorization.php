<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Contracts\Authentication;

/**
 * Class Authorization
 * Inspired by : https://github.com/spatie/laravel-authorize
 * @package Modules\Core\Http\Middleware
 */
class Authorization
{
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * Authorization constructor.
     * @param Authentication $auth
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle($request, \Closure $next, $permission)
    {
        if ($this->auth->hasAccess($permission) === false) {
            return $this->handleUnauthorizedRequest($request, $permission);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    private function handleUnauthorizedRequest(Request $request, $permission)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response('Unauthorized.', Response::HTTP_FORBIDDEN);
        }
        if ($request->user() === null) {
            return redirect()->guest('auth/login');
        }

        return redirect()->back()
            ->withError(trans('core::core.permission denied', ['permission' => $permission]));
    }
}
