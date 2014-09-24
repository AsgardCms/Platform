<?php
if (!function_exists('user_asset')) {
    function user_asset($url, array $attributes = [], $secure = false)
    {
        return Module::asset('user', $url, $attributes, $secure);
    }
}