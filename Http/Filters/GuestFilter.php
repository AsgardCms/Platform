<?php namespace Modules\User\Http\Filters;

use Illuminate\Routing\Redirector;
use Modules\Core\Contracts\Authentication;

class GuestFilter
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var Redirector
     */
    private $redirect;

    public function __construct(Authentication $auth, Redirector $redirect)
    {
        $this->auth = $auth;
        $this->redirect = $redirect;
    }

    public function filter()
    {
        if ($this->auth->check()) {
            return $this->redirect->route('dashboard.index');
        }
    }
}
