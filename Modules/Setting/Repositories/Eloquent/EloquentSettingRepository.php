<?php

namespace Modules\Setting\Repositories\Eloquent;

use Illuminate\Support\Facades\Config;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Events\SettingIsCreating;
use Modules\Setting\Events\SettingIsUpdating;
use Modules\Setting\Events\SettingWasCreated;
use Modules\Setting\Events\SettingWasUpdated;
use Modules\Setting\Repositories\SettingRepository;

class EloquentSettingRepository extends EloquentBaseRepository implements SettingRepository
{
    /**
     * Update a resource
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
    }

    /**
     * Return all settings, with the setting name as key
     * @return array
     */
    public function all()
    {
        $rawSettings = parent::all();

        $settings = [];
        foreach ($rawSettings as $setting) {
            $settings[$setting->name] = $setting;
        }

        return $settings;
    }

    /**
     * Create or update the settings
     * @param $settings
     * @return mixed|void
     */
    public function createOrUpdate($settings)
    {
        $this->removeTokenKey($settings);

        foreach ($settings as $settingName => $settingValues) {
            if ($setting = $this->findByName($settingName)) {
                $this->updateSetting($setting, $settingValues);
                continue;
            }
            $this->createForName($settingName, $settingValues);
        }
    }

    /**
     * Remove the token from the input array
     * @param $settings
     */
    private function removeTokenKey(&$settings)
    {
        unset($settings['_token']);
    }

    /**
     * Find a setting by its name
     * @param $settingName
     * @return mixed
     */
    public function findByName($settingName)
    {
        return $this->model->where('name', $settingName)->first();
    }

    /**
     * Create a setting with the given name
     * @param string $settingName
     * @param $settingValues
     * @return Setting
     */
    private function createForName($settingName, $settingValues)
    {
        event($event = new SettingIsCreating($settingName, $settingValues));

        $setting = new $this->model();
        $setting->name = $settingName;

        if ($this->isTranslatableSetting($settingName)) {
            $setting->isTranslatable = true;
            $this->setTranslatedAttributes($event->getSettingValues(), $setting);
        } else {
            $setting->isTranslatable = false;
            $setting->plainValue = $this->getSettingPlainValue($event->getSettingValues());
        }

        $setting->save();

        event(new SettingWasCreated($setting));

        return $setting;
    }

    /**
     * Update the given setting
     * @param object setting
     * @param $settingValues
     */
    private function updateSetting($setting, $settingValues)
    {
        $name = $setting->name;
        event($event = new SettingIsUpdating($setting, $name, $settingValues));

        if ($this->isTranslatableSetting($name)) {
            $this->setTranslatedAttributes($event->getSettingValues(), $setting);
        } else {
            $setting->plainValue = $this->getSettingPlainValue($event->getSettingValues());
        }
        $setting->save();

        event(new SettingWasUpdated($setting));

        return $setting;
    }

    /**
     * @param $settingValues
     * @param $setting
     */
    private function setTranslatedAttributes($settingValues, $setting)
    {
        foreach ($settingValues as $lang => $value) {
            $setting->translateOrNew($lang)->value = $value;
        }
    }

    /**
     * Return all modules that have settings
     * with its settings
     * @param  array|string $modules
     * @return array
     */
    public function moduleSettings($modules)
    {
        if (is_string($modules)) {
            return config('asgard.' . strtolower($modules) . ".settings");
        }

        $modulesWithSettings = [];
        foreach ($modules as $module) {
            if ($moduleSettings = config('asgard.' . strtolower($module->getName()) . ".settings")) {
                $modulesWithSettings[$module->getName()] = $moduleSettings;
            }
        }

        return $modulesWithSettings;
    }

    /**
     * Return the saved module settings
     * @param $module
     * @return mixed
     */
    public function savedModuleSettings($module)
    {
        $moduleSettings = [];
        foreach ($this->findByModule($module) as $setting) {
            $moduleSettings[$setting->name] = $setting;
        }

        return $moduleSettings;
    }

    /**
     * Find settings by module name
     * @param  string $module Module name
     * @return mixed
     */
    public function findByModule($module)
    {
        return $this->model->where('name', 'LIKE', $module . '::%')->get();
    }

    /**
     * Find the given setting name for the given module
     * @param  string $settingName
     * @return mixed
     */
    public function get($settingName)
    {
        return $this->model->where('name', 'LIKE', "{$settingName}")->first();
    }

    /**
     * Return the translatable module settings
     * @param $module
     * @return mixed
     */
    public function translatableModuleSettings($module)
    {
        return array_filter($this->moduleSettings($module), function ($setting) {
            return isset($setting['translatable']) && $setting['translatable'] === true;
        });
    }

    /**
     * Return the non translatable module settings
     * @param $module
     * @return array
     */
    public function plainModuleSettings($module)
    {
        return array_filter($this->moduleSettings($module), function ($setting) {
            return !isset($setting['translatable']) || $setting['translatable'] === false;
        });
    }

    /**
     * Return a setting name using dot notation: asgard.{module}.settings.{settingName}
     * @param string $settingName
     * @return string
     */
    private function getConfigSettingName($settingName)
    {
        list($module, $setting) = explode('::', $settingName);

        return "asgard.{$module}.settings.{$setting}";
    }

    /**
     * Check if the given setting name is translatable
     * @param string $settingName
     * @return bool
     */
    private function isTranslatableSetting($settingName)
    {
        $configSettingName = $this->getConfigSettingName($settingName);

        $setting = config("$configSettingName");

        return isset($setting['translatable']) && $setting['translatable'] === true;
    }

    /**
     * Return the setting value(s). If values are ann array, json_encode them
     * @param string|array $settingValues
     * @return string
     */
    private function getSettingPlainValue($settingValues)
    {
        if (is_array($settingValues)) {
            return json_encode($settingValues);
        }

        return $settingValues;
    }
}
