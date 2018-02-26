<?php

namespace Modules\Workshop\Manager;

use FloatingPoint\Stylist\Theme\Exceptions\ThemeNotFoundException;
use FloatingPoint\Stylist\Theme\Json;
use FloatingPoint\Stylist\Theme\Theme;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;

class StylistThemeManager implements ThemeManager
{
    /**
     * @var Filesystem
     */
    private $finder;

    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return array
     */
    public function all()
    {
        $directories = $this->getDirectories();

        $themes = [];
        foreach ($directories as $directory) {
            $themes[] = $this->getThemeInfoForPath($directory);
        }

        return $themes;
    }

    /**
     * @param string $themeName
     * @return Theme
     * @throws ThemeNotFoundException
     */
    public function find($themeName)
    {
        foreach ($this->getDirectories() as $directory) {
            if (strtolower(basename($directory)) !== strtolower($themeName)) {
                continue;
            }

            return $this->getThemeInfoForPath($directory);
        }

        throw new ThemeNotFoundException($themeName);
    }

    /**
     * @param string $directory
     * @return Theme
     */
    private function getThemeInfoForPath($directory)
    {
        $themeJson = new Json($directory);

        $theme = new Theme(
            $themeJson->getJsonAttribute('name'),
            $themeJson->getJsonAttribute('description'),
            $directory,
            $themeJson->getJsonAttribute('parent')
        );
        $theme->version = $themeJson->getJsonAttribute('version');
        $theme->type = ucfirst($themeJson->getJsonAttribute('type'));
        $theme->changelog = $this->getChangelog($directory);
        $theme->active = $this->getStatus($theme);

        return $theme;
    }

    /**
     * Get all theme directories
     * @return array
     */
    private function getDirectories()
    {
        $themePath = config('stylist.themes.paths', [base_path('/Themes')]);

        return $this->finder->directories($themePath[0]);
    }

    /**
     * @param string $directory
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getChangelog($directory)
    {
        if (! $this->finder->isFile($directory . '/changelog.yml')) {
            return [];
        }

        $yamlFile = $this->finder->get($directory . '/changelog.yml');

        $yamlParser = new Parser();

        $changelog = $yamlParser->parse($yamlFile);

        $changelog['versions'] = $this->limitLastVersionsAmount(array_get($changelog, 'versions', []));

        return $changelog;
    }

    /**
     * Limit the versions to the last 5
     * @param array $versions
     * @return array
     */
    private function limitLastVersionsAmount(array $versions)
    {
        return array_slice($versions, 0, 5);
    }

    /**
     * Check if the theme is active based on its type
     * @param Theme $theme
     * @return bool
     */
    private function getStatus(Theme $theme)
    {
        if ($theme->type !== 'Backend') {
            return setting('core::template') === $theme->getName();
        }

        return config('asgard.core.core.admin-theme') === $theme->getName();
    }
}
