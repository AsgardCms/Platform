<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Modules\Core\Downloader\Downloader;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DownloadModuleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:download:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the given module';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $downloader = new Downloader($this->getOutput());
        $downloader->download($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The vendor/name of the module'],
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
           // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
