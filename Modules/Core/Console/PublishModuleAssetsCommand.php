<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class PublishModuleAssetsCommand extends Command
{
    protected $name = 'asgard:publish:module';
    protected $description = 'Publish module assets';

    public function handle()
    {
        $this->call('module:publish', ['module' => $this->argument('module')]);
    }

    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module name'],
        ];
    }
}
