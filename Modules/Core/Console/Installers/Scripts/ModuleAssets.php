<?php

namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;

class ModuleAssets implements SetupScript
{
    /**
     * @var array
     */
    protected $modules = [
        'Core',
        'Media',
        'Menu',
    ];

    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        if ($command->option('verbose')) {
            $command->blockMessage('Module assets', 'Publishing module assets ...', 'comment');
        }

        foreach ($this->modules as $module) {
            if ($command->option('verbose')) {
                $command->call('module:publish', ['module' => $module]);
                continue;
            }
            $command->callSilent('module:publish', ['module' => $module]);
        }
    }
}
