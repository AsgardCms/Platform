<?php

namespace Modules\Media\UrlResolvers;

use League\Flysystem\Adapter\Ftp;

class FtpUrlResolver
{
    /**
     * @param Ftp  $adapter
     * @param string $path
     * @return string
     */
    public function resolve(Ftp $adapter, $path)
    {
        return 'ftp://' . config('filesystems.disks.ftp.host') . $path;
    }
}
