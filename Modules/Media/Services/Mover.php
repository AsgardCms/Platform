<?php

namespace Modules\Media\Services;

use Modules\Media\Entities\File;

interface Mover
{
    public function move(File $file, File $destination) : bool;
}
