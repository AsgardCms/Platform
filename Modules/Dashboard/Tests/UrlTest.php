<?php namespace Modules\Dashboard\Tests;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\Core\Tests\BaseTestCase;

class UrlTest extends BaseTestCase
{
    /** @test */
    public function testDashboardAccessible()
    {
        $user = Sentinel::findById(4);
        Sentinel::login($user);

        $this->checkResponseIsOkAndContains(['GET', '/' . Config::get('core::core.admin-prefix')], 'h1:contains("Dashboard")');
    }
}
