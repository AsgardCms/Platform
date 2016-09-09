<?php

namespace Modules\Workshop\Scaffold\Theme\Traits;

trait FindsThemePath
{
    /**
     * Get the theme location path
     * @param string $name
     * @return string
     */
    public function themePath($name = '')
    {
        return config('asgard.core.core.themes_path') . "/$name";
    }

    /**
     * Get the theme location path
     * @param string $name
     * @param string $file
     * @return string
     */
    public function themePathForFile($name, $file)
    {
        return config('asgard.core.core.themes_path') . "/$name/$file";
    }
}
