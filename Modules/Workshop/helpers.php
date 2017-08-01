<?php

if (! function_exists('module_version')) {
    function module_version($module)
    {
        if (is_core_module($module->name) === true) {
            return \Modules\Core\AsgardCms::VERSION;
        }
        return $module->version;
    }
}
