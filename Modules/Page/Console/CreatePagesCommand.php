<?php

namespace Modules\Page\Console;

use Illuminate\Console\Command;
use Modules\Page\Repositories\PageRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreatePagesCommand extends Command
{
    protected $name = 'asgard:create:test-pages';
    protected $description = 'Command description.';
    /**
     * @var PageRepository
     */
    private $page;

    public function __construct(PageRepository $page)
    {
        parent::__construct();
        $this->page = $page;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $total = 10000;
        $bar = $this->output->createProgressBar($total);

        for ($i = 0; $i < $total; $i++) {
            $this->createPage();
            $bar->advance();
        }
        $bar->finish();
    }

    private function createPage()
    {
        $faker = \Faker\Factory::create();

        $this->page->create([
            'is_home' => 0,
            'template' => 'default',
            'en' => [
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
            'fr' => [
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
            'nl' => [
                'title' => $faker->name,
                'slug' => $faker->slug,
                'body' => $faker->paragraph(),
            ],
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            //['example', InputArgument::REQUIRED, 'An example argument.'],
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
            //['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
