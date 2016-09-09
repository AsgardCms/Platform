<?php

namespace Modules\Media\UrlResolvers;

use League\Flysystem\AwsS3v3\AwsS3Adapter;

class AwsS3UrlResolver
{
    /**
     * @param AwsS3Adapter $adapter
     * @param string $path
     * @return string
     */
    public function resolve(AwsS3Adapter $adapter, $path)
    {
        return $adapter->getClient()->getObjectUrl(config('filesystems.disks.s3.bucket'), ltrim($path, '/'));
    }
}
