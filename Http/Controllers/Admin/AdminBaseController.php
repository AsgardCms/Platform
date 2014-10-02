<?php namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class AdminBaseController extends Controller
{
    public function __construct()
    {
        $this->beforeFilter('auth.admin');
    }
}