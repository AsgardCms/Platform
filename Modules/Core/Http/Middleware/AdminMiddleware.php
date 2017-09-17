<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store;
use Modules\User\Contracts\Authentication;

class AdminMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var SessionManager
     */
    private $session;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Redirector
     */
    private $redirect;
    /**
     * @var Application
     */
    private $application;

    public function __construct(Authentication $auth, Store $session, Request $request, Redirector $redirect, Application $application)
    {
        $this->auth = $auth;
        $this->session = $session;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->application = $application;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Check if the user is logged in
        if (!$this->auth->check()) {
            if ($request->ajax()) {
                return response('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
            }
            // Store the current uri in the session
            $this->session->put('url.intended', $this->request->url());

            // Redirect to the login page
            return $this->redirect->route('login');
        }

        // Check if the user has access to the dashboard page
        if (! $this->auth->hasAccess('dashboard.index')) {
            // Show the insufficient permissions page
            return $this->application->abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
