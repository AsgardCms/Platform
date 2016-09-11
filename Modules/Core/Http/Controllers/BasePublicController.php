<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Modules\User\Contracts\Authentication;

abstract class BasePublicController extends Controller
{
    /**
     * @var Authentication
     */
    protected $auth;
    public $locale;

    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->auth = app(Authentication::class);
    }
}
