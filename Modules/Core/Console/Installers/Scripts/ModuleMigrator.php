<?php

namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;

class ModuleMigrator implements SetupScript
{
    /**
     * @var array
     */
    protected $modules = [
        'Setting',
        'Menu',
        'Media',
        'Page',
        'Dashboard',
        'Translation',
        'Tag',
    ];

    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        if ($command->option('verbose')) {
            $command->blockMessage('Migrations', 'Starting the module migrations ...', 'comment');
        }

        foreach ($this->modules as $module) {
            if ($command->option('verbose')) {
                $command->call('module:migrate', ['module' => $module]);
                continue;
            }
            $command->callSilent('module:migrate', ['module' => $module]);
        }
    }
}
