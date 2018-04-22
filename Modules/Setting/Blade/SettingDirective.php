<?php

namespace Modules\Setting\Blade;

final class SettingDirective
{
    /**
     * @var string
     */
    private $settingName;
    /**
     * @var string
     */
    private $locale;
    /**
     * @var string Default value
     */
    private $default;

    /**
     * @param $arguments
     */
    public function show($arguments)
    {
        $this->extractArguments($arguments);

        return setting($this->settingName, $this->locale, $this->default);
    }

    /**
     * Check if a setting is set and is not empty
     * @param array $arguments
     * @return boolean
     */
    public function has($arguments)
    {
        $value = $this->show($arguments);

        if (empty($value)) {
            return false;
        }    
        return true;
    }

    /**
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->settingName = array_get($arguments, 0);
        $this->locale = array_get($arguments, 1);
        $this->default = array_get($arguments, 2);
    }
}
