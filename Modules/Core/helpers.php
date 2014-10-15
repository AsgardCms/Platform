<?php

if (!function_exists('core_asset')) {
    function core_asset($url, array $attributes = [], $secure = false)
    {
        return Module::asset('core', $url, $attributes, $secure);
    }
}