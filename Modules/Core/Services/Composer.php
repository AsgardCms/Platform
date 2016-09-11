<?php

namespace Modules\Core\Services;

use Symfony\Component\Process\Process;

class Composer extends \Illuminate\Support\Composer
{
    protected $outputHandler = null;
    private $output;

    /**
     * Enable real time output of all commands.
     *
     * @param $command
     * @return void
     */
    public function enableOutput($command)
    {
        $this->output = function ($type, $buffer) use ($command) {
            if (Process::ERR === $type) {
                $command->info(trim('[ERR] > ' . $buffer));
            } else {
                $command->info(trim('> ' . $buffer));
            }
        };
    }

    /**
     * Disable real time output of all commands.
     *
     * @return void
     */
    public function disableOutput()
    {
        $this->output = null;
    }

    /**
     * Update all composer packages.
     *
     * @param  string $package
     * @return void
     */
    public function update($package = null)
    {
        if (!is_null($package)) {
            $package = '"' . $package . '"';
        }
        $process = $this->getProcess();
        $process->setCommandLine(trim($this->findComposer() . ' update ' . $package));
        $process->run($this->output);
    }

    /**
     * Require a new composer package.
     *
     * @param  string $package
     * @return void
     */
    public function install($package)
    {
        if (!is_null($package)) {
            $package = '"' . $package . '"';
        }
        $process = $this->getProcess();
        $process->setCommandLine(trim($this->findComposer() . ' require ' . $package));
        $process->run($this->output);
    }

    /**
     * @return void
     */
    public function dumpAutoload()
    {
        $process = $this->getProcess();
        $process->setCommandLine(trim($this->findComposer() . ' dump-autoload -o'));
        $process->run($this->output);
    }

    public function remove($package)
    {
        if (!is_null($package)) {
            $package = '"' . $package . '"';
        }
        $process = $this->getProcess();
        $process->setCommandLine(trim($this->findComposer() . ' remove ' . $package));
        $process->run($this->output);
    }
}
