<?php

namespace Modules\Media\Tests;

use Modules\Media\Image\ThumbnailManager;

class ThumbnailsManagerTest extends MediaTestCase
{
    /**
     * @var ThumbnailManager
     */
    private $thumbnailManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->thumbnailManager = app(ThumbnailManager::class);
    }

    /** @test */
    public function it_initialises_empty_array()
    {
        $this->assertCount(2, $this->thumbnailManager->all());
    }

    /** @test */
    public function it_can_add_a_thumbnail()
    {
        $this->thumbnailManager->registerThumbnail('coolThumb', []);

        $this->assertCount(3, $this->thumbnailManager->all());
    }

    /** @test */
    public function it_can_find_a_thumbnail()
    {
        $expected = [
            'resize' => [
                'width' => 180,
                'height' => null,
                'callback' => function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                },
            ],
        ];

        $this->assertEquals($expected, $this->thumbnailManager->find('mediumThumb'));
    }

    /** @test */
    public function it_returns_empty_array_if_no_thumbnail_found()
    {
        $this->assertEquals([], $this->thumbnailManager->find('someRandomThumb'));
    }
}
