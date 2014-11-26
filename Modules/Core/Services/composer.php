<?php namespace Modules\Core\Services;

class Composer extends \Illuminate\Foundation\Composer
{
    protected $outputHandler = null;
    private $output;

    /**
     * Enable real time output of all commands.
     *
     * @param $handler
     * @return void
     */
    public function enableOutput($handler)
    {
        $this->output = $handler;
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
     * @param string $package
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
     * @param string $package
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
}
