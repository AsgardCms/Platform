<?php

namespace Modules\Media\Tests;

use Modules\Media\ValueObjects\MediaPath;

class MediaPathTest extends MediaTestCase
{
    /** @test */
    public function it_can_instantiate_value_object()
    {
        $path = new MediaPath('some/path.jpg');

        $this->assertInstanceOf(MediaPath::class, $path);
    }

    /** @test */
    public function it_only_accepts_a_string_as_argument()
    {
        $this->expectException(\InvalidArgumentException::class);

        new MediaPath(['something']);
    }

    /** @test */
    public function it_can_get_the_url()
    {
        $path = new MediaPath('some/path.jpg');

        $this->assertEquals('http://localhost/some/path.jpg', $path->getUrl());
    }

    /** @test */
    public function it_can_get_the_relative_url()
    {
        $path = new MediaPath('some/path.jpg');

        $this->assertEquals('some/path.jpg', $path->getRelativeUrl());
    }

    /** @test */
    public function it_casts_media_path_to_string_using_url_method()
    {
        $path = new MediaPath('some/path.jpg');

        $this->assertEquals('http://localhost/some/path.jpg', (string) $path);
        $this->assertNotEquals('some/path.jpg', (string) $path);
    }
}
