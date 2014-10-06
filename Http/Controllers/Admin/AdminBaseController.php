<?php namespace Modules\Core\Http\Controllers\Admin;

/**
 * @Before("auth.admin")
 */
class AdminBaseController
{
    public function __construct()
    {
        //$this->beforeFilter('auth.admin');
    }
}