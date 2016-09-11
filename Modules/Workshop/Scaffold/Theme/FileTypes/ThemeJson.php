<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class ThemeJson extends BaseFileType implements FileType
{
    use FindsThemePath;

    /**
     * Generate the current file type
     * @return string
     */
    public function generate()
    {
        $stub = $this->finder->get(__DIR__ . '/../stubs/themeJson.stub');

        $stub = $this->replaceContentInStub($stub);

        $this->finder->put($this->themePathForFile($this->options['name'], 'theme.json'), $stub);
    }

    public function replaceContentInStub($stub)
    {
        return str_replace(
            [
                '{{theme-name}}',
                '{{type}}',
            ],
            [
                $this->options['name'],
                $this->options['type'],
            ],
            $stub
        );
    }
}
