<?php

namespace Modules\Workshop\Scaffold\Module\Generators;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class Generator
{
    /**
     * @var Filesystem
     */
    protected $finder;
    /**
     * @var string Module Name
     */
    protected $name;
    /**
     * @var string The type of entities to generate [Eloquent or Doctrine]
     */
    protected $entityType;
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Filesystem $finder, Repository $config)
    {
        $this->finder = $finder;
        $this->config = $config;
    }

    /**
     * Generate the given files
     * @param  array $files
     * @return void
     */
    abstract public function generate(array $files);

    /**
     * Set the module name
     * @param  string $moduleName
     * @return $this
     */
    public function forModule($moduleName)
    {
        $this->name = Str::studly($moduleName);

        return $this;
    }

    /**
     * Set the entity type on the class


     *
     *@param  string $entityType
     * @return EntityGenerator
     */
    public function type($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Return the current module path
     * @param  string $path
     * @return string
     */
    protected function getModulesPath($path = '')
    {
        return $this->config->get('modules.paths.modules') . "/{$this->name}/$path";
    }

    /**
     * Get the path the stubs for the given filename
     *
     * @param $filename
     * @return string
     */
    protected function getStubPath($filename)
    {
        $folder = $this->config->get('asgard.workshop.config.custom-stubs-folder');

        if ($folder !== null) {
            $file = realpath($folder . '/' . $filename);
            if ($file !== false) {
                return $file;
            }
        }

        return __DIR__ . "/../stubs/$filename";
    }

    /**
     * Write the given content to the given file
     * @param string $path
     * @param string $content
     */
    protected function writeFile($path, $content)
    {
        $this->finder->put("$path.php", $content);
    }

    /**
     * @param  string                                       $stub
     * @param  string                                       $class
     * @return string
     * @throws FileNotFoundException
     */
    protected function getContentForStub($stub, $class)
    {
        $stub = $this->finder->get($this->getStubPath($stub));

        return str_replace(
            [
                '$MODULE_NAME$',
                '$LOWERCASE_MODULE_NAME$',
                '$PLURAL_LOWERCASE_MODULE_NAME$',
                '$CLASS_NAME$',
                '$LOWERCASE_CLASS_NAME$',
                '$PLURAL_LOWERCASE_CLASS_NAME$',
                '$PLURAL_CLASS_NAME$',
                '$ENTITY_TYPE$',
            ],
            [
                $this->name,
                strtolower($this->name),
                strtolower(str_plural($this->name)),
                $class,
                strtolower($class),
                strtolower(str_plural($class)),
                str_plural($class),
                $this->entityType,
            ],
            $stub
        );
    }
}
