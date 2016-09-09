<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class AssetsFolder extends BaseFileType implements FileType
{
    use FindsThemePath;

    /**
     * Generate the current file type
     * @return string
     */
    public function generate()
    {
        $stub = $this->finder->get(__DIR__ . '/../stubs/gitignore.stub');

        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/assets/css'), 0755, true);
        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/assets/js'), 0755, true);
        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/assets/images'), 0755, true);

        $this->finder->put($this->themePathForFile($this->options['name'], '/assets/.gitignore'), $stub);
        $this->finder->put($this->themePathForFile($this->options['name'], '/assets/css/.gitignore'), $stub);
        $this->finder->put($this->themePathForFile($this->options['name'], '/assets/js/.gitignore'), $stub);
        $this->finder->put($this->themePathForFile($this->options['name'], '/assets/images/.gitignore'), $stub);
    }
}
