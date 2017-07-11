<?php

namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;

class ThemeAssets implements SetupScript
{
    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        if ($command->option('verbose')) {
            $command->blockMessage('Themes', 'Publishing theme assets ...', 'comment');
        }

        if ($command->option('verbose')) {
            $command->call('stylist:publish');

            return;
        }
        $command->callSilent('stylist:publish');
    }
}
