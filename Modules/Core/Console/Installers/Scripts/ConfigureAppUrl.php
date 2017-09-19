<?php

namespace Modules\Core\Console\Installers\Scripts;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Modules\Core\Console\Installers\SetupScript;
use Modules\Core\Console\Installers\Writers\EnvFileWriter;

class ConfigureAppUrl implements SetupScript
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var EnvFileWriter
     */
    protected $env;

    /**
     * @param Config        $config
     * @param EnvFileWriter $env
     */
    public function __construct(Config $config, EnvFileWriter $env)
    {
        $this->config = $config;
        $this->env = $env;
    }

    /**
     * @var Command
     */
    protected $command;

    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command)
    {
        $this->command = $command;
        
        $vars = [];

        $vars['app_url'] = $this->askAppUrl();
        
        $this->setLaravelConfiguration($vars);

        $this->env->write($vars);

        $command->info('Application url successfully configured');
    }

    /**
     * @return string
     */
    protected function askAppUrl()
    {
        $str = $this->command->ask('Enter you application url (e.g. http://localhost, http://www.example.com)', 'http://localhost');

        return $str;
    }

    

    /**
     * @param $vars
     */
    protected function setLaravelConfiguration($vars)
    {
        $this->config['app.url'] = $vars['app_url'];
     
    }
    
}
