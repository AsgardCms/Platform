<?php namespace Modules\Media\Tests;

use Modules\Core\Tests\BaseTestCase;
use Modules\Media\Croppy\Croppy;

class CroppyTest extends BaseTestCase
{
    /**
     * @var Croppy
     */
    protected $croppy;

    public function setUp()
    {
        $this->refreshApplication();
        $this->croppy = new Croppy($this->app['filesystem.disk'], $this->app['config']);
    }

    /** @test */
    public function it_should_return_an_extension()
    {
    }
}
