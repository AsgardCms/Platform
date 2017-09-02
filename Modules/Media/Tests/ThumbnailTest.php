<?php

namespace Modules\Tests;

use Modules\Media\Image\Thumbnail;

class ThumbnailTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_creates_thumbnail_class()
    {
        $thumbnail = Thumbnail::make($this->getBlogThumbnailConfig());

        $this->assertInstanceOf('Modules\Media\Image\Thumbnail', $thumbnail);
    }

    /** @test */
    public function it_gets_thumbnail_name()
    {
        $thumbnail = Thumbnail::make($this->getBlogThumbnailConfig());

        $this->assertEquals('blogThumb', $thumbnail->name());
    }

    /** @test */
    public function it_gets_thumbnail_filters()
    {
        $thumbnail = Thumbnail::make($this->getBlogThumbnailConfig());

        $expected = [
            'resize' => [
                'width' => 150,
                'height' => 250,
            ],
            'fit' => [
                'width' => 550,
                'height' => 650,
            ],
        ];
        $this->assertEquals($expected, $thumbnail->filters());
    }

    /** @test */
    public function it_gets_thumbnail_width()
    {
        $thumbnail = Thumbnail::make($this->getBlogThumbnailConfig());

        $this->assertSame(150, $thumbnail->width());
    }

    /** @test */
    public function it_gets_thumbnail_height()
    {
        $thumbnail = Thumbnail::make($this->getBlogThumbnailConfig());

        $this->assertSame(250, $thumbnail->height());
    }

    /** @test */
    public function it_gets_thumbnail_size()
    {
        $thumbnail = Thumbnail::make($this->getBlogThumbnailConfig());

        $this->assertSame('150x250', $thumbnail->size());
    }

    /** @test */
    public function it_gets_multiple_thumbnails()
    {
        $thumbnails = Thumbnail::makeMultiple($this->getMediaThumbnails());

        $this->assertCount(2, $thumbnails);
        $this->assertEquals('smallThumb', $thumbnails[0]->name());
        $this->assertEquals('mediumThumb', $thumbnails[1]->name());
    }

    private function getBlogThumbnailConfig()
    {
        return [
            'blogThumb' => [
                'resize' => [
                    'width' => 150,
                    'height' => 250,
                ],
                'fit' => [
                    'width' => 550,
                    'height' => 650,
                ],
            ],
        ];
    }

    private function getMediaThumbnails()
    {
        return [
            'smallThumb' => [
                'resize' => [
                    'width' => 50,
                    'height' => null,
                    'callback' => function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    },
                ],
            ],
            'mediumThumb' => [
                'resize' => [
                    'width' => 180,
                    'height' => null,
                    'callback' => function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    },
                ],
            ],
        ];
    }
}
