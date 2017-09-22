<?php

namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\SetupScript;
use Modules\Core\Console\Installers\Writers\EnvFileWriter;

class SetInstalledFlag implements SetupScript
{
    /**
     * @var EnvFileWriter
     */
    protected $env;

    public function __construct(EnvFileWriter $env)
    {
        $this->env = $env;
    }

    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        $vars = [];

        $vars['installed'] = 'true';

        $this->env->write($vars);

        $command->info('The application is now installed');
    }
}
