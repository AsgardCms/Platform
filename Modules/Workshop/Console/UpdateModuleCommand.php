<?php

namespace Modules\Workshop\Console;

use Illuminate\Console\Command;
use Modules\Core\Services\Composer;
use Symfony\Component\Console\Input\InputArgument;

class UpdateModuleCommand extends Command
{
    protected $name = 'asgard:module:update';
    protected $description = 'Update a module';

    /**
     * @var \Modules\Core\Services\Composer
     */
    private $composer;

    public function __construct(Composer $composer)
    {
        parent::__construct();
        $this->composer = $composer;
    }

    public function handle()
    {
        $packageName = $this->getModulePackageName($this->argument('module'));

        $this->composer->enableOutput($this);
        $this->composer->update($packageName);
    }

    /**
     * Make the full package name for the given module name
     * @param string $module
     * @return string
     */
    private function getModulePackageName($module)
    {
        return "asgardcms/{$module}-module";
    }

    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module name'],
        ];
    }
}
