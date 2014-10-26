<?php namespace Modules\Core\Composers;

use Modules\Core\Contracts\Authentication;

abstract class BaseSidebarViewComposer
{
    /**
     * @var Authentication
     */
    protected $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }
}
