<?php

namespace Modules\User\Composers;

use Illuminate\Contracts\View\View;
use Modules\User\Contracts\Authentication;

class UsernameViewComposer
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function compose(View $view)
    {
        $view->with('user', $this->auth->user());
    }
}
