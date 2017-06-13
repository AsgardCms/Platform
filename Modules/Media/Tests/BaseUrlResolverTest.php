<?php

namespace Modules\Media\Tests;

use Modules\Media\UrlResolvers\BaseUrlResolver;

class BaseUrlResolverTest extends MediaTestCase
{
    /** @test */
    public function it_returns_correct_local_uri()
    {
        config()->set('asgard.media.config.filesystem', 'local');

        $resolver = new BaseUrlResolver();
        $resolvedPath = $resolver->resolve('/assets/media/my_image.png');

        $this->assertEquals(config('app.url') . '/assets/media/my_image.png', $resolvedPath);
    }

    public function it_returns_correct_aws_s3_uri()
    {
        config()->set('asgard.media.config.filesystem', 's3');
        config()->set('filesystems.disks.s3.bucket', 'testing-bucket');
        config()->set('filesystems.disks.s3.region', 'eu-west-1');

        $resolver = new BaseUrlResolver();
        $resolvedPath = $resolver->resolve('/assets/media/my_image.png');

        $this->assertEquals('https://testing-bucket.s3-eu-west-1.amazonaws.com/assets/media/my_image.png', $resolvedPath);
    }
}
