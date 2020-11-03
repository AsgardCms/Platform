<?php

namespace Modules\Setting\Blade;

use Illuminate\Support\Arr;

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

        return e(setting($this->settingName, $this->locale, $this->default));
    }

    /**
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->settingName = Arr::get($arguments, 0);
        $this->locale = Arr::get($arguments, 1);
        $this->default = Arr::get($arguments, 2);
    }
}
