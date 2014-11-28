<?php namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class PublishThemeAssetsCommand extends Command
{
    protected $name = 'asgard:publish:theme';
    protected $description = 'Publish theme assets';

    public function fire()
    {
        $this->info('Publishing assets for ' . $this->argument('theme'));
    }

    protected function getArguments()
    {
        return array(
            array('theme', InputArgument::REQUIRED, 'The theme name')
        );
    }
}
