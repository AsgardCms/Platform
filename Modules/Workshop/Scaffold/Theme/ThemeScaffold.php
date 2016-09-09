<?php

namespace Modules\Workshop\Scaffold\Theme;

use Illuminate\Filesystem\Filesystem;
use Modules\Workshop\Scaffold\Theme\Exceptions\ThemeExistsException;
use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class ThemeScaffold
{
    use FindsThemePath;

    /**
     * @var array
     */
    protected $files = [
        'themeJson',
        'composerJson',
        'masterBladeLayout',
        'basicView',
        'resourcesFolder',
        'assetsFolder',
    ];
    /**
     * Options array containing:
     *  - name
     *  - type
     * @var array
     */
    protected $options;

    /**
     * @var ThemeGeneratorFactory
     */
    private $themeGeneratorFactory;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;

    public function __construct(ThemeGeneratorFactory $themeGeneratorFactory, Filesystem $finder)
    {
        $this->themeGeneratorFactory = $themeGeneratorFactory;
        $this->finder = $finder;
    }

    /**
     * @throws Exceptions\FileTypeNotFoundException
     * @throws ThemeExistsException
     */
    public function generate()
    {
        if ($this->finder->isDirectory($this->themePath($this->options['name']))) {
            throw new ThemeExistsException("The theme [{$this->options['name']}] already exists");
        }

        $this->finder->makeDirectory($this->themePath($this->options['name']));

        foreach ($this->files as $file) {
            $this->themeGeneratorFactory->make($file, $this->options)->generate();
        }

        $this->addThemeToIgnoredExceptions();
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('You must provide a name');
        }

        $this->options['name'] = $name;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function forType($type)
    {
        if (empty($type)) {
            throw new \InvalidArgumentException('You must provide a type');
        }

        $this->options['type'] = $type;

        return $this;
    }

    public function setVendor($vendor)
    {
        if (empty($vendor)) {
            throw new \InvalidArgumentException('You must provide a vendor name');
        }

        $this->options['vendor'] = $vendor;

        return $this;
    }

    /**
     * Set the files array on the class
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * Adding the theme name to the .gitignore file so that it can be committed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function addThemeToIgnoredExceptions()
    {
        $themePath = config('asgard.core.core.themes_path');

        if ($this->finder->exists($themePath . '/.gitignore') === false) {
            return;
        }
        $moduleGitIgnore = $this->finder->get($themePath . '/.gitignore');
        $moduleGitIgnore .= '!' . $this->options['name'] . PHP_EOL;
        $this->finder->put($themePath . '/.gitignore', $moduleGitIgnore);
    }
}
