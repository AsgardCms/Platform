<?php

namespace Modules\Translation\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Translation\Repositories\TranslationRepository;

class BuildTranslationsCacheCommand extends Command
{
    use DispatchesJobs;

    protected $name = 'asgard:build:translations';
    protected $description = 'Build the translations cache';
    /**
     * @var
     */
    private $translation;

    public function __construct(TranslationRepository $translation)
    {
        parent::__construct();
        $this->translation = $translation;
    }

    public function handle()
    {
        foreach ($this->translation->all() as $translation) {
            foreach (config('laravellocalization.supportedLocales') as $locale => $language) {
                $this->translation->findByKeyAndLocale($translation->key, $locale);
            }
        }
        $this->info('All translations were cached.');
    }
}
