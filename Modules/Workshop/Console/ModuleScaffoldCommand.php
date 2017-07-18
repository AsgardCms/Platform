<?php

namespace Modules\Workshop\Console;

use Illuminate\Console\Command;
use Modules\Workshop\Scaffold\Module\ModuleScaffold;

class ModuleScaffoldCommand extends Command
{
    protected $name = 'asgard:module:scaffold';
    protected $description = 'Scaffold a new module';
    /**
     * @var array
     */
    protected $entities = [];
    /**
     * @var array
     */
    protected $valueObjects = [];
    /**
     * @var string The type of entities to generate [Eloquent or Doctrine]
     */
    protected $entityType;
    /**
     * @var ModuleScaffold
     */
    private $moduleScaffold;

    public function __construct(ModuleScaffold $moduleScaffold)
    {
        parent::__construct();
        $this->moduleScaffold = $moduleScaffold;
    }

    /**
     *
     */
    public function fire()
    {
        $moduleName = $this->ask('Please enter the module name in the following format: vendor/name');
        list($vendor, $name) = $this->separateVendorAndName($moduleName);

        $this->checkForModuleUniqueness($name);

        $this->askForEntities();
        $this->askForValueObjects();

        $this->moduleScaffold
            ->vendor($vendor)
            ->name($name)
            ->setEntityType($this->entityType)
            ->withEntities($this->entities)
            ->withValueObjects($this->valueObjects)
            ->scaffold();

        $this->info('Module generated and is ready to be used.');
    }

    /**
     *
     */
    private function askForEntities()
    {
        $this->entityType = 'Eloquent';

        do {
            $entity = $this->ask('Enter entity name. Leaving option empty will continue script.', '<none>');
            if (!empty($entity) && $entity !== '<none>') {
                $this->entities[] = ucfirst($entity);
            }
        } while ($entity !== '<none>');
    }

    /**
     *
     */
    private function askForValueObjects()
    {
        do {
            $valueObject = $this->ask('Enter value object name. Leaving option empty will continue script.', '<none>');
            if (!empty($valueObject) && $valueObject !== '<none>') {
                $this->valueObjects[] = ucfirst($valueObject);
            }
        } while ($valueObject !== '<none>');
    }

    /**
     * Extract the vendor and module name as two separate values
     * @param  string $fullName
     * @return array
     */
    private function separateVendorAndName($fullName)
    {
        $explodedFullName = explode('/', $fullName);

        return [
            $explodedFullName[0],
            ucfirst($explodedFullName[1]),
        ];
    }

    /**
     * Check if the given module name does not already exists
     *
     * @param string $name
     */
    private function checkForModuleUniqueness($name)
    {
        /** @var \Illuminate\Filesystem\Filesystem $files */
        $files = app('Illuminate\Filesystem\Filesystem');
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = app('Illuminate\Contracts\Config\Repository');
        if ($files->isDirectory($config->get('modules.paths.modules') . "/{$name}")) {
            return $this->error("The module [$name] already exists");
        }
    }
}
