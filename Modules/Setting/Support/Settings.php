<?php namespace Modules\Setting\Support;

use Modules\Core\Contracts\Setting;
use Modules\Setting\Repositories\SettingRepository;

class Settings implements Setting
{
    /**
     * @var SettingRepository
     */
    private $setting;

    /**
     * @param SettingRepository $setting
     */
    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Getting the setting
     * @param string $name
     * @param null $locale
     * @param null $default
     * @return mixed
     */
    public function get($name, $locale = null, $default = null)
    {
        $setting = $this->setting->get($name);

        if ($setting) {
            if ($setting->isTranslatable) {
                return $setting->translate($locale)->value;
            }

            return $setting->plainValue;
        }

        return $default;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        $default = microtime(true);

        return $this->get($name, null, $default) !== $default;
    }

    /**
     * Set a given configuration value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value)
    {
    }
}
