<?php namespace Modules\Workshop\Manager;

use Illuminate\Config\Repository as Config;
use Pingpong\Modules\Module;

class ModuleManager
{
    /**
     * @var Module
     */
    private $module;
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Module $module
     * @param Config $config
     */
    public function __construct(Module $module, Config $config)
    {
        $this->module = $module;
        $this->config = $config;
    }

    /**
     * Return all modules
     * @return array
     */
    public function all()
    {
        return $this->module->all();
    }

    /**
     * Return all the enabled modules
     * @return array
     */
    public function enabled()
    {
        return $this->module->enabled();
    }

    /**
     * Get the core modules that shouldn't be disabled
     * @return array|mixed
     */
    public function getCoreModules()
    {
        $coreModules = $this->config->get('core::config.CoreModules');
        $coreModules = array_flip($coreModules);

        return $coreModules;
    }

    /**
     * Get the enabled modules, with the module name as the key
     * @return array
     */
    public function getFlippedEnabledModules()
    {
        $enabledModules = $this->module->enabled();
        $enabledModules = array_flip($enabledModules);

        return $enabledModules;
    }

    /**
     * Disable the given modules
     * @param $enabledModules
     */
    public function disableModules($enabledModules)
    {
        $coreModules = $this->getCoreModules();

        foreach ($enabledModules as $moduleToDisable => $value) {
            if (isset($coreModules[$moduleToDisable])) {
                continue;
            }
            $this->module->disable($moduleToDisable);
        }
    }

    /**
     * Enable the given modules
     * @param $modules
     */
    public function enableModules($modules)
    {
        foreach ($modules as $moduleToEnable => $value) {
            $this->module->enable($moduleToEnable);
        }
    }
}
