<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{

    public $theme;

    public function __construct()
    {
        $this->theme = 'demo';
    }

    /**
     * @Get("/")
     */
    public function index()
    {
        return view('index');
    }

}
