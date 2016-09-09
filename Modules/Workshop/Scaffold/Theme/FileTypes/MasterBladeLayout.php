<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class MasterBladeLayout extends BaseFileType implements FileType
{
    use FindsThemePath;

    /**
     * Generate the current file type
     * @return string
     */
    public function generate()
    {
        $stub = $this->finder->get(__DIR__ . '/../stubs/masterBladeLayout.stub');

        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/views/layouts'), 0755, true);

        $this->finder->put($this->themePathForFile($this->options['name'], '/views/layouts/master.blade.php'), $stub);
    }
}
