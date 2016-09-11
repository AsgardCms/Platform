<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

interface FileType
{
    /**
     * Generate the current file type
     * @return string
     */
    public function generate();
}
