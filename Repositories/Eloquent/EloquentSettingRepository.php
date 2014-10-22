<?php namespace Modules\Setting\Repositories\Eloquent;

use Illuminate\Support\Facades\Config;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
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
     * @param mixed $data
     * @return mixed
     */
    public function create($data)
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
            // Check if setting exists
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
        return $this->model->whereHas(
            'translations',
            function ($q) use ($settingName) {
                $q->where('name', $settingName);
            }
        )->first();
    }

    /**
     * Create a setting with the given name
     * @param $settingName
     * @param $settingValues
     */
    private function createForName($settingName, $settingValues)
    {
        $setting = new $this->model;
        $setting->name = $settingName;
        $this->setTranslatedAttributes($settingValues, $setting);

        return $setting->save();
    }

    /**
     * Update the given setting
     * @param $setting
     * @param $settingValues
     */
    private function updateSetting($setting, $settingValues)
    {
        $this->setTranslatedAttributes($settingValues, $setting);

        return $setting->save();
    }

    /**
     * @param $settingValues
     * @param $setting
     */
    private function setTranslatedAttributes($settingValues, $setting)
    {
        foreach ($settingValues as $lang => $value) {
            $setting->translate($lang)->value = $value;
        }
    }

    /**
     * Return all modules that have settings
     * with its settings
     * @param array|string $modules
     * @return array
     */
    public function moduleSettings($modules)
    {
        if (is_string($modules)) {
            return Config::get(strtolower($modules) . "::settings");
        }

        $modulesWithSettings = [];
        foreach ($modules as $module) {
            if ($moduleSettings = Config::get(strtolower($module) . "::settings")) {
                $modulesWithSettings[$module] = $moduleSettings;
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
     * @param string $module Module name
     * @return mixed
     */
    public function findByModule($module)
    {
        return $this->model->where('name', 'LIKE', $module . '_%')->get();
    }

    /**
     * Find the given setting name for the given module
     * @param $settingName
     * @param $module
     * @return mixed
     */
    public function findSettingForModule($settingName, $module = null)
    {
        if (is_null($module)) {
            return $this->model->where('name', 'LIKE', "%{$settingName}")->first();
        }

        return $this->model->where('name', 'LIKE', "{$module}_{$settingName}")->first();
    }
}
