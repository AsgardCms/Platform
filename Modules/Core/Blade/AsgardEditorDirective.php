<?php

namespace Modules\Core\Blade;

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
        $this->fieldName = array_get($arguments, 0);
        $this->labelName = array_get($arguments, 1);
        $this->content = array_get($arguments, 2);
        $this->lang = array_get($arguments, 3);
    }
}
