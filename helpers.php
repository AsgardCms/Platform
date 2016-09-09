<?php

if (! function_exists('setting')) {
    function setting($name, $locale = null, $default = null)
    {
        return app('setting.settings')->get($name, $locale, $default);
    }
}
