<?php

namespace Modules\Workshop\Scaffold\Theme\FileTypes;

use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class ComposerJson extends BaseFileType implements FileType
{
    use FindsThemePath;

    /**
     * Generate the current file type
     * @return string
     */
    public function generate()
    {
        $stub = $this->finder->get(__DIR__ . '/../stubs/composerJson.stub');

        $stub = $this->replaceContentInStub($stub);

        $this->finder->put($this->themePathForFile($this->options['name'], 'composer.json'), $stub);
    }

    public function replaceContentInStub($stub)
    {
        return str_replace(
            [
                '{{theme-name}}',
                '{{vendor}}',
            ],
            [
                $this->options['name'],
                $this->options['vendor'],
            ],
            $stub
        );
    }
}
