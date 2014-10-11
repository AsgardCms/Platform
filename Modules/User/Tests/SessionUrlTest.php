<?php namespace Modules\User\Tests;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Config;
use TestCase;

class SessionUrlTest extends TestCase
{
    /** @test */
    public function loginPageShouldBeAccessible()
    {
        $crawler = $this->client->request('GET', '/auth/login');

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertCount(1, $crawler->filter('.header:contains("Sign In")'));
    }

    /** @test */
    public function registerPageShouldBeAccessible()
    {
        $crawler = $this->client->request('GET', '/auth/register');

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertCount(1, $crawler->filter('.header:contains("Register New Membership")'));
    }

    /** @test */
    public function forgotPasswordShouldBeAccessible()
    {
        $crawler = $this->client->request('GET', '/auth/reset');

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertCount(1, $crawler->filter('.header:contains("Reset Password")'));
    }

    /** @test */
    public function dashboardShouldNotBePubliclyAccessible()
    {
        $this->app['router']->enableFilters();
        Sentinel::logout();

        $crawler = $this->client->request('GET', '/' . Config::get('core::core.admin-prefix'));

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

        $crawler = $this->client->request('GET', '/' . Config::get('core::core.admin-prefix'));

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Dashboard")'));
    }
}