<?php namespace Modules\User\Tests;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Config;
use Modules\Core\Tests\BaseTestCase;

class SessionUrlTest extends BaseTestCase
{
    /** @test */
    public function loginPageShouldBeAccessible()
    {
        $this->checkResponseIsOkAndContains(['GET', '/auth/login'], '.header:contains("Sign In")');
    }

    /** @test */
    public function registerPageShouldBeAccessible()
    {
        $this->checkResponseIsOkAndContains(['GET', '/auth/register'], '.header:contains("Register New Membership")');
    }

    /** @test */
    public function forgotPasswordShouldBeAccessible()
    {
        $this->checkResponseIsOkAndContains(['GET', '/auth/reset'], '.header:contains("Reset Password")');
    }

    /** @test */
    public function dashboardShouldNotBePubliclyAccessible()
    {
        $this->app['router']->enableFilters();
        Sentinel::logout();

        $this->client->request('GET', '/' . Config::get('core::core.admin-prefix'));

        $this->assertRedirectedTo('auth/login');
    }

    /** @test */
    public function dashboardShouldBeAccessibleIfLoggedIn()
    {
        // $sentinelMock = Mockery::mock('Cartalyst\Sentinel\Laravel\Facades\Sentinel');
        // $sentinelMock->shouldReceive('check')->andReturn(true);
        $this->app['router']->enableFilters();
        $user = Sentinel::findById(4);
        Sentinel::login($user);

        $this->checkResponseIsOkAndContains(['GET', '/' . Config::get('core::core.admin-prefix')], 'h1:contains("Dashboard")');
    }

}
