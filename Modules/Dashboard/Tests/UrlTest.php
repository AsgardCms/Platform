<?php

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UrlTest extends TestCase
{
    /** @test */
    public function testDashboardAccessible()
    {
        $user = Sentinel::findById(4);
        Sentinel::login($user);

        $crawler = $this->client->request('GET', '/' . Config::get('core::core.admin-prefix'));

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertCount(1, $crawler->filter('h1:contains("Dashboard")'));
    }
}
