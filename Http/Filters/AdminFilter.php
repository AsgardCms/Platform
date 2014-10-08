<?php namespace Modules\Core\Http\Filters;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class AdminFilter
{
    public function filter()
    {
        // Check if the user is logged in
        if (!Sentinel::check()) {
            // Store the current uri in the session
            Session::put('loginRedirect', Request::url());

            // Redirect to the login page
            return Redirect::route('login');
        }

        // Check if the user has access to the dashboard page
        if ( ! Sentinel::getUser()->hasAccess('dashboard.index'))
        {
            // Show the insufficient permissions page
            return App::abort(403);
        }
    }
}