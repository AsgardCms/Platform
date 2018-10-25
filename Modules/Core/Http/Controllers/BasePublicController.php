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
    public $alternateUrls = [];

    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->auth = app(Authentication::class);
        view()->share('alternate', $this->alternateUrls);
    }

    /**
     * Add alternate URLs to main array and inject it to the page
     *
     * @param array $alternateUrls
     * @return void
     */
    protected function addAlternateUrls(array $alternateUrls)
    {
        $this->alternateUrls = array_merge($this->alternateUrls, $alternateUrls);
        view()->share('alternate', $this->alternateUrls);
    }
}
