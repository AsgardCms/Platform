<?php

namespace Modules\User\Http\Middleware;

use Modules\User\Contracts\Authentication;

/**
 * Class LoggedInMiddleware
 * @package Modules\User\Http\Middleware
 * Middleware to make sure affected routes need a logged in user
 */
class LoggedInMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ($this->auth->check() === false) {
            return redirect()->guest(config('asgard.user.config.redirect_route_not_logged_in', 'auth/login'));
        }

        return $next($request);
    }
}
