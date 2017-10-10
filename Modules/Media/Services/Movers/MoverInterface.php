<?php

namespace Modules\Media\Services\Movers;

use Modules\Media\Entities\File;

interface MoverInterface
{
    public function move(File $file, File $destination) : bool;
}
