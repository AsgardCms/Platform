<?php

namespace Modules\Core\Traits;

trait CanPublishConfiguration
{
    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param string $module
     * @param string $fileName
     */
    public function publishConfig($module, $fileName)
    {
        if (app()->environment() === 'testing') {
            return;
        }

        $this->mergeConfigFrom($this->getModuleConfigFilePath($module, $fileName), strtolower("asgard.$module.$fileName"));
        $this->publishes([
            $this->getModuleConfigFilePath($module, $fileName) => config_path(strtolower("asgard/$module/$fileName") . '.php'),
        ], 'config');
    }

    /**
     * Get path of the give file name in the given module
     * @param string $module
     * @param string $file
     * @return string
     */
    private function getModuleConfigFilePath($module, $file)
    {
        return $this->getModulePath($module) . "/Config/$file.php";
    }

    /**
     * @param $module
     * @return string
     */
    private function getModulePath($module)
    {
        return base_path('Modules' . DIRECTORY_SEPARATOR . ucfirst($module));
    }
}
