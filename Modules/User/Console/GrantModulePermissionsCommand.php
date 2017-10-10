<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\User\Permissions\PermissionsAdder;
use Symfony\Component\Console\Input\InputArgument;

class GrantModulePermissionsCommand extends Command
{
    protected $name = 'asgard:user:grant-permissions';
    protected $description = 'Grant all the permissions to the admin role of given module';

    public function handle()
    {
        $module = $this->argument('module');

        (new PermissionsAdder($module))->addAll();

        $this->info("All permissions for [$module] have been added to the admin role");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name'],
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
        ];
    }
}
