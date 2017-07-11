<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Modules\Core\Downloader\Downloader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

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
     * @return mixed
     * @throws \Symfony\Component\Process\Exception\LogicException
     */
    public function fire()
    {
        $downloader = new Downloader($this->getOutput());
        try {
            if ($this->hasOption('branch')) {
                $downloader->forBranch($this->option('branch'));
            }
            $downloader->download($this->argument('name'));
        } catch (\Exception $e) {
            $this->output->writeln("<error>{$e->getMessage()}</error>");

            return;
        }

        $name = $this->extractPackageNameFrom($this->argument('name'));

        $composer = $this->findComposer();
        $commands = [
            $composer . ' dump-autoload',
        ];
        if ($this->option('migrations') === true || $this->option('demo') === true) {
            $commands[] = "php artisan module:migrate $name";
        }
        if ($this->option('seeds') === true || $this->option('demo') === true) {
            $commands[] = "php artisan module:seed $name";
        }
        if ($this->option('assets') === true || $this->option('demo') === true) {
            $commands[] = "php artisan module:publish $name";
        }
        $process = new Process(implode(' && ', $commands));
        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }
        $output = $this->getOutput();
        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });
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
            ['migrations', 'm', InputOption::VALUE_NONE, 'Run the module migrations', null],
            ['seeds', 's', InputOption::VALUE_NONE, 'Run the module seeds', null],
            ['assets', 'a', InputOption::VALUE_NONE, 'Publish the module assets', null],
            ['demo', 'd', InputOption::VALUE_NONE, 'Run all optional flags', null],
            ['branch', null, InputOption::VALUE_OPTIONAL, 'Download a specific branch name', null],
        ];
    }

    private function extractPackageNameFrom($package)
    {
        if (str_contains($package, '/') === false) {
            throw new \Exception('You need to use vendor/name structure');
        }

        return studly_case(substr(strrchr($package, '/'), 1));
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd() . '/composer.phar')) {
            return '"' . PHP_BINARY . '" composer.phar';
        }

        return 'composer';
    }
}
