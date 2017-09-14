<?php

if (! function_exists('module_version')) {
    function module_version(\Nwidart\Modules\Laravel\Module $module)
    {
        if (is_core_module($module->getName()) === true) {
            return \Modules\Core\Foundation\AsgardCms::VERSION;
        }

        return $module->version;
    }
}

if (! function_exists('theme_version')) {
    function theme_version(\FloatingPoint\Stylist\Theme\Theme $theme)
    {
        if (is_core_theme($theme->getName()) === true) {
            return \Modules\Core\Foundation\AsgardCms::VERSION;
        }

        return $theme->version;
    }
}
