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
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->settingName = array_get($arguments, 0);
        $this->locale = array_get($arguments, 1);
        $this->default = array_get($arguments, 2);
    }
}
