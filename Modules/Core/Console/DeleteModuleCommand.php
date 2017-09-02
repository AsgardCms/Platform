<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Modules\User\Permissions\PermissionsRemover;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeleteModuleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:delete:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a module and optionally its migrations';
    /**
     * @var Filesystem
     */
    private $finder;

    public function __construct(Filesystem $finder)
    {
        parent::__construct();

        $this->finder = $finder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');

        $extra = '';
        if ($this->option('migrations') === true) {
            $extra = ' and reset its tables';
        }
        if ($this->confirm("Are you sure you wish to delete the [$module] module{$extra}?") === false) {
            $this->info('Nothing was deleted');

            return;
        }

        $modulePath = config('modules.paths.modules') . '/' . $module;

        if ($this->finder->exists($modulePath) === false) {
            $this->error('This module does not exist');

            return;
        }

        if (is_core_module($module) === true) {
            $this->error('You cannot remove a core module.');

            return;
        }

        if ($this->option('migrations') === true) {
            $this->call('module:migrate-reset', ['module' => $module]);
        }

        $this->removePermissionsFor($module);

        $this->finder->deleteDirectory($modulePath);
        $this->info('Module successfully deleted');
    }

    private function removePermissionsFor($module)
    {
        (new PermissionsRemover($module))->removeAll();

        $this->info("All permissions for [$module] have been removed");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module name'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['migrations', 'm', InputOption::VALUE_NONE, 'Reset the module migrations', null],
        ];
    }
}
