<?php

namespace Modules\Core\Blade;

use Illuminate\Support\Arr;

class AsgardEditorDirective
{
    private $content;
    private $lang;
    private $fieldName;
    private $labelName;

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        if ($this->lang !== null) {
            return asgard_i18n_editor($this->fieldName, $this->labelName, $this->content, $this->lang);
        }

        return asgard_editor($this->fieldName, $this->labelName, $this->content);
    }

    /**
     * Extract the possible arguments as class properties
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->fieldName = Arr::get($arguments, 0);
        $this->labelName = Arr::get($arguments, 1);
        $this->content = Arr::get($arguments, 2);
        $this->lang = Arr::get($arguments, 3);
    }
}
