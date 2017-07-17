<?php

namespace Modules\Core\Traits;

trait CanGetSidebarClassForModule
{
    /**
     * @param string $module
     * @param string $default
     * @return string
     */
    public function getSidebarClassForModule($module, $default)
    {
        if ($this->hasCustomSidebar($module)) {
            $class = config("asgard.{$module}.config.custom-sidebar");
            if (class_exists($class) === false) {
                return $default;
            }

            return $class;
        }

        return $default;
    }

    private function hasCustomSidebar($module)
    {
        $config = config("asgard.{$module}.config.custom-sidebar");

        return $config !== null;
    }
}
