<?php

namespace Modules\Workshop\Scaffold\Module;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Modules\Workshop\Scaffold\Module\Exception\ModuleExistsException;
use Modules\Workshop\Scaffold\Module\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Module\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\Module\Generators\ValueObjectGenerator;

class ModuleScaffold
{
    /**
     * Contains the vendor name
     * @var string
     */
    protected $vendor;
    /**
     * Contains the Module name
     * @var string
     */
    protected $name;
    /**
     * Contains an array of entities to generate
     * @var array
     */
    protected $entities;
    /**
     * Contains an array of value objects to generate
     * @var array
     */
    protected $valueObjects;
    /**
     * @var array of files to generate
     */
    protected $files = [
        'permissions.stub' => 'Config/permissions',
        'routes.stub' => 'Http/backendRoutes',
        'route-provider.stub' => 'Providers/RouteServiceProvider',
    ];
    /**
     * @var string The type of entities to generate [Eloquent or Doctrine]
     */
    protected $entityType;
    /**
     * @var Kernel
     */
    private $artisan;
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var Repository
     */
    private $config;
    /**
     * @var EntityGenerator
     */
    private $entityGenerator;
    /**
     * @var ValueObjectGenerator
     */
    private $valueObjectGenerator;
    /**
     * @var FilesGenerator
     */
    private $filesGenerator;

    public function __construct(
        Filesystem $finder,
        Repository $config,
        EntityGenerator $entityGenerator,
        ValueObjectGenerator $valueObjectGenerator,
        FilesGenerator $filesGenerator
    ) {
        $this->artisan = app('Illuminate\Contracts\Console\Kernel');
        $this->finder = $finder;
        $this->config = $config;
        $this->entityGenerator = $entityGenerator;
        $this->valueObjectGenerator = $valueObjectGenerator;
        $this->filesGenerator = $filesGenerator;
    }

    /**
     *
     */
    public function scaffold()
    {
        if ($this->finder->isDirectory($this->getModulesPath())) {
            throw new ModuleExistsException();
        }

        $this->artisan->call("module:make", ['name' => [$this->name]]);

        $this->addDataToComposerFile();
        $this->removeUnneededFiles();
        $this->addFolders();

        $this->filesGenerator->forModule($this->name)
            ->generateModuleProvider()
            ->generateEventProvider()
            ->generate($this->files);

        $this->cleanUpModuleJson();

        $this->entityGenerator->forModule($this->getName())->type($this->entityType)->generate($this->entities);
        $this->valueObjectGenerator->forModule($this->getName())->type($this->entityType)->generate($this->valueObjects);
    }

    /**
     * @param  string $vendor
     * @return $this
     */
    public function vendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of module will created. By default in studly case.
     *
     * @return string
     */
    public function getName()
    {
        return Str::studly($this->name);
    }

    /**
     * Set the entity type [Eloquent, Doctrine]
     * @param  string $entityType
     * @return $this
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @param  array $entities
     * @return $this
     */
    public function withEntities(array $entities)
    {
        $this->entities = $entities;

        return $this;
    }

    /**
     * @param  array $valueObjects
     * @return $this
     */
    public function withValueObjects(array $valueObjects)
    {
        $this->valueObjects = $valueObjects;

        return $this;
    }

    /**
     * Return the current module path
     * @param  string $path
     * @return string
     */
    private function getModulesPath($path = '')
    {
        return $this->config->get('modules.paths.modules') . "/{$this->getName()}/$path";
    }

    /**
     * Rename the default vendor name 'pingpong-modules'
     * by the input vendor name
     */
    private function renameVendorName()
    {
        $composerJsonContent = $this->finder->get($this->getModulesPath('composer.json'));
        $composerJsonContent = str_replace('nwidart', $this->vendor, $composerJsonContent);
        $this->finder->put($this->getModulesPath('composer.json'), $composerJsonContent);
    }

    /**
     * Remove the default generated view resources
     */
    private function removeViewResources()
    {
        $this->finder->delete($this->getModulesPath('Resources/views/index.blade.php'));
        $this->finder->delete($this->getModulesPath('Resources/views/layouts/master.blade.php'));
        $this->finder->deleteDirectory($this->getModulesPath('Resources/views/layouts'));
    }

    /**
     * Remove all unneeded files
     */
    private function removeUnneededFiles()
    {
        $this->renameVendorName();
        $this->removeViewResources();

        $this->finder->delete($this->getModulesPath("Http/Controllers/{$this->name}Controller.php"));
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function cleanUpModuleJson()
    {
        $moduleJson = $this->finder->get($this->getModulesPath('module.json'));

        $moduleJson = $this->loadProviders($moduleJson);
        $moduleJson = $this->setModuleOrderOrder($moduleJson);
        $moduleJson = $this->setModuleVersion($moduleJson);

        $this->finder->put($this->getModulesPath('module.json'), $moduleJson);
    }

    /**
     * Load the routing service provider
     * @param string $content
     * @return string
     */
    private function loadProviders($content)
    {
        $newProviders = <<<JSON
"Modules\\\\{$this->name}\\\Providers\\\\{$this->name}ServiceProvider",
        "Modules\\\\{$this->name}\\\Providers\\\\EventServiceProvider",
        "Modules\\\\{$this->name}\\\Providers\\\RouteServiceProvider"
JSON;

        $oldProvider = '"Modules\\\\' . $this->name . '\\\\Providers\\\\' . $this->name . 'ServiceProvider"';

        return  str_replace($oldProvider, $newProviders, $content);
    }

    /**
     * Set the module order to 1
     * @param string $content
     * @return string
     */
    private function setModuleOrderOrder($content)
    {
        return str_replace('"priority": 0,', '"priority": 1,', $content);
    }

    /**
     * Set the module version to 1.0.0 by default
     * @param string $content
     * @return string
     */
    private function setModuleVersion($content)
    {
        return str_replace("\"description\"", "\"version\": \"1.0.0\",\n\t\"description\"", $content);
    }

    /**
     * Add required folders
     */
    private function addFolders()
    {
        $this->finder->makeDirectory($this->getModulesPath('Sidebar'));
        $this->finder->makeDirectory($this->getModulesPath('Repositories/Cache'));
    }

    /**
     * Add more data in composer json
     * - a asgard/module type
     * - package requirements
     * - minimum stability
     * - prefer stable
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function addDataToComposerFile()
    {
        $composerJson = $this->finder->get($this->getModulesPath('composer.json'));

        $name = ucfirst($this->name);

        $search = <<<JSON
"description": "",
JSON;
        $replace = <<<JSON
"description": "",
    "type": "asgard-module",
    "license": "MIT",
    "require": {
        "php": "^7.1.3",
        "composer/installers": "~1.0",
        "idavoll/core-module": "4.0.x-dev"
    },
    "require-dev": {
        "phpunit/phpunit": "~7.0",
        "orchestra/testbench": "3.8.*"
    },
    "autoload-dev": {
        "psr-4": {
            "Modules\\\\$name\\\\": ".",
            "Modules\\\\": "Modules/"
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true,
JSON;
        $composerJson = str_replace($search, $replace, $composerJson);
        $this->finder->put($this->getModulesPath('composer.json'), $composerJson);
    }

    /**
     * Adding the module name to the .gitignore file so that it can be committed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function addModuleToIgnoredExceptions()
    {
        $modulePath = $this->config->get('modules.paths.modules');

        if ($this->finder->exists($modulePath . '/.gitignore') === false) {
            return;
        }
        $moduleGitIgnore = $this->finder->get($modulePath . '/.gitignore');
        $moduleGitIgnore .= '!' . $this->getName() . PHP_EOL;
        $this->finder->put($modulePath . '/.gitignore', $moduleGitIgnore);
    }
}
