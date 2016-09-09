<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class ResourcesFolder extends BaseFileType implements FileType
{
    use FindsThemePath;

    /**
     * Generate the current file type
     * @return string
     */
    public function generate()
    {
        $stub = $this->finder->get(__DIR__ . '/../stubs/gitignore.stub');

        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/resources/css'), 0755, true);
        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/resources/js'), 0755, true);
        $this->finder->makeDirectory($this->themePathForFile($this->options['name'], '/resources/images'), 0755, true);

        $this->finder->put($this->themePathForFile($this->options['name'], '/resources/.gitignore'), $stub);
        $this->finder->put($this->themePathForFile($this->options['name'], '/resources/css/.gitignore'), $stub);
        $this->finder->put($this->themePathForFile($this->options['name'], '/resources/js/.gitignore'), $stub);
        $this->finder->put($this->themePathForFile($this->options['name'], '/resources/images/.gitignore'), $stub);
    }
}
