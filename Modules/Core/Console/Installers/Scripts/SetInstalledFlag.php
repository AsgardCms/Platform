<?php

namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Modules\Core\Console\Installers\SetupScript;

class SetInstalledFlag implements SetupScript
{
    /**
     * @var Filesystem
     */
    private $finder;

    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        $env = $this->finder->get('.env');
        $env = str_replace('INSTALLED=false', 'INSTALLED=true', $env);
        $this->finder->put('.env', $env);
    }
}
