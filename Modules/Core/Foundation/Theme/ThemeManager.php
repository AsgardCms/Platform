<?php

namespace Modules\Core\Foundation\Theme;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class ThemeManager implements \Countable
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var string Path to scan for themes
     */
    private $path;

    /**
     * @param Application $app
     * @param $path
     */
    public function __construct(Application $app, $path)
    {
        $this->app = $app;
        $this->path = $path;
    }

    /**
     * @param  string     $name
     * @return Theme|null
     */
    public function find($name)
    {
        foreach ($this->all() as $theme) {
            if ($theme->getLowerName() == strtolower($name)) {
                return $theme;
            }
        }

        return;
    }

    /**
     * Return all available themes
     * @return array
     */
    public function all()
    {
        $themes = [];
        if (!$this->getFinder()->isDirectory($this->path)) {
            return $themes;
        }

        $directories = $this->getDirectories();

        foreach ($directories as $theme) {
            if (Str::startsWith($name = basename($theme), '.')) {
                continue;
            }
            $themes[$name] = new Theme($name, $theme);
        }

        return $themes;
    }

    /**
     * Get only the public themes
     * @return array
     */
    public function allPublicThemes()
    {
        $themes = [];
        if (!$this->getFinder()->isDirectory($this->path)) {
            return $themes;
        }

        $directories = $this->getDirectories();

        foreach ($directories as $theme) {
            if (Str::startsWith($name = basename($theme), '.')) {
                continue;
            }
            $themeJson = $this->getThemeJsonFile($theme);
            if ($this->isFrontendTheme($themeJson)) {
                $themes[$name] = new Theme($name, $theme);
            }
        }

        return $themes;
    }

    /**
     * Get the theme directories
     * @return array
     */
    private function getDirectories()
    {
        return $this->getFinder()->directories($this->path);
    }

    /**
     * Return the theme assets path
     * @param  string $theme
     * @return string
     */
    public function getAssetPath($theme)
    {
        return public_path($this->getConfig()->get('themify.themes_assets_path') . '/' . $theme);
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getFinder()
    {
        return $this->app['files'];
    }

    /**
     * @return \Illuminate\Config\Repository
     */
    protected function getConfig()
    {
        return $this->app['config'];
    }

    /**
     * Counts all themes
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Returns the theme json file
     * @param $theme
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getThemeJsonFile($theme)
    {
        return json_decode($this->getFinder()->get("$theme/theme.json"));
    }

    /**
     * @param $themeJson
     * @return bool
     */
    private function isFrontendTheme($themeJson)
    {
        return isset($themeJson->type) && $themeJson->type !== 'frontend' ? false : true;
    }
}
